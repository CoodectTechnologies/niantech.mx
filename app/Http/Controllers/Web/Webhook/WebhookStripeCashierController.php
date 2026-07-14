<?php

namespace App\Http\Controllers\Web\Webhook;

use App\Models\Plan;
use Carbon\Carbon;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Stripe\Subscription as StripeSubscription;
use Symfony\Component\HttpFoundation\Response;

class WebhookStripeCashierController extends CashierWebhookController
{
    /**
     * Handle customer subscription created.
     *
     * @return Response
     */
    protected function handleCustomerSubscriptionCreated(array $payload) {
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        if ($user) {
            $data = $payload['data']['object'];

            if (! $user->subscriptions->contains('stripe_id', $data['id'])) {
                if (isset($data['trial_end'])) {
                    $trialEndsAt = Carbon::createFromTimestamp($data['trial_end']);
                } else {
                    $trialEndsAt = null;
                }

                $firstItem = $data['items']['data'][0];
                $isSinglePrice = count($data['items']['data']) === 1;
                $stripePrice = $isSinglePrice ? $firstItem['price']['id'] : null;
                $plan = Plan::where('stripe_price_month_id', $stripePrice)->orWhere('stripe_price_year_id', $stripePrice)->first();
                $subscription = $user->subscriptions()->updateOrCreate([
                    'stripe_id' => $data['id'],
                ], [
                    'plan_id' => $plan->id ?? null,
                    'type' => $data['metadata']['type'] ?? $data['metadata']['name'] ?? $this->newSubscriptionType($payload),
                    'stripe_status' => $data['status'],
                    'stripe_price' => $stripePrice,
                    'quantity' => $isSinglePrice && isset($firstItem['quantity']) ? $firstItem['quantity'] : null,
                    'trial_ends_at' => $trialEndsAt,
                    'ends_at' => null,
                ]);

                foreach ($data['items']['data'] as $item) {
                    $subscription->items()->updateOrCreate([
                        'stripe_id' => $item['id'],
                    ], [
                        'stripe_product' => $item['price']['product'],
                        'stripe_price' => $item['price']['id'],
                        'quantity' => $item['quantity'] ?? null,
                    ]);
                }
            }

            // Terminate the billable's generic trial if it exists...
            if (! is_null($user->trial_ends_at)) {
                $user->trial_ends_at = null;
                $user->saveQuietly();
            }
        }

        return $this->successMethod();
    }

    /**
     * Determines the type that should be used when new subscriptions are created from the Stripe dashboard.
     *
     * @return string
     */
    protected function newSubscriptionType(array $payload) {
        $stripeProductName = Plan::query()->whereNotNull('stripe_product_name')->value('stripe_product_name');
        $subscriptionName = $stripeProductName;

        return $subscriptionName ?? 'default';
    }

    /**
     * Handle customer subscription updated.
     *
     * @return Response
     */
    protected function handleCustomerSubscriptionUpdated(array $payload) {
        if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {
            $data = $payload['data']['object'];

            $subscription = $user->subscriptions()->firstOrNew(['stripe_id' => $data['id']]);

            if (
                isset($data['status']) &&
                $data['status'] === StripeSubscription::STATUS_INCOMPLETE_EXPIRED
            ) {
                $subscription->items()->delete();
                $subscription->delete();

                return;
            }

            $subscription->type = $subscription->type ?? $data['metadata']['type'] ?? $data['metadata']['name'] ?? $this->newSubscriptionType($payload);

            $firstItem = $data['items']['data'][0];
            $isSinglePrice = count($data['items']['data']) === 1;

            // Price...
            $stripePrice = $isSinglePrice ? $firstItem['price']['id'] : null;
            $plan = Plan::where('stripe_price_month_id', $stripePrice)->orWhere('stripe_price_year_id', $stripePrice)->first();

            $subscription->stripe_price = $stripePrice;
            $subscription->plan_id = $plan->id ?? null;

            // Quantity...
            $subscription->quantity = $isSinglePrice && isset($firstItem['quantity']) ? $firstItem['quantity'] : null;

            // Trial ending date...
            if (isset($data['trial_end'])) {
                $trialEnd = Carbon::createFromTimestamp($data['trial_end']);

                if (! $subscription->trial_ends_at || $subscription->trial_ends_at->ne($trialEnd)) {
                    $subscription->trial_ends_at = $trialEnd;
                }
            }

            // Cancellation date...
            if ($data['cancel_at_period_end'] ?? false) {
                $subscription->ends_at = $subscription->onTrial()
                    ? $subscription->trial_ends_at
                    : Carbon::createFromTimestamp($data['current_period_end']);
            } elseif (isset($data['cancel_at']) || isset($data['canceled_at'])) {
                $subscription->ends_at = Carbon::createFromTimestamp($data['cancel_at'] ?? $data['canceled_at']);
            } else {
                $subscription->ends_at = null;
            }

            // Status...
            if (isset($data['status'])) {
                $subscription->stripe_status = $data['status'];
            }

            $subscription->save();

            // Update subscription items...
            if (isset($data['items'])) {
                $subscriptionItemIds = [];

                foreach ($data['items']['data'] as $item) {
                    $subscriptionItemIds[] = $item['id'];

                    $subscription->items()->updateOrCreate([
                        'stripe_id' => $item['id'],
                    ], [
                        'stripe_product' => $item['price']['product'],
                        'stripe_price' => $item['price']['id'],
                        'quantity' => $item['quantity'] ?? null,
                    ]);
                }

                // Delete items that aren't attached to the subscription anymore...
                $subscription->items()->whereNotIn('stripe_id', $subscriptionItemIds)->delete();
            }
        }

        return $this->successMethod();
    }
}
