<?php

namespace App\Livewire\Admin\Subscription\Billing;

use App\Models\Plan;
use App\Models\User;
use App\Notifications\Subscription\SubscriptionCreate;
use Exception;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Livewire\Component;

class Index extends Component
{
    public $user;
    public $planType = 'month';
    public $stripeIntent;
    public $stripeHasDefaultPaymentMethod;
    public $pendingPlanId = null;

    public function mount(User $user) {
        $this->user = $user;
        $this->loadPlanType();
    }
    public function render() {
        $plans = Plan::query()->where('status', true)->orderBy('order', 'asc')->get();
        $paymentMethods = $this->user->paymentMethods();
        $invoices = $this->user->invoices();
        $this->user = $this->user->refresh();

        return view('livewire.admin.subscription.billing.index', compact('plans', 'paymentMethods', 'invoices'));
    }

    // SUBSCRIPTIONS
    public function newSubscription($planId) {
        $plan = Plan::find($planId);
        if (! $plan) {
            $this->dispatch('alert', 'error', 'El plan seleccionado no es válido');

            return;
        }
        if (! $this->user->hasDefaultPaymentMethod()) {
            // Guardamos el plan pendiente y pedimos al front que abra el modal de nuevo método
            $this->pendingPlanId = $plan->id;
            $this->dispatch('openPaymentModalForPlan', $plan->id);

            return;
        }
        try {
            $priceId = $this->planType === 'month' ? $plan->stripe_price_month_id : $plan->stripe_price_year_id;
            if ($this->user->subscribed($plan->stripe_product_name)) {
                $subscription = $this->user->subscription($plan->stripe_product_name);
                $subscription->swap($priceId);
                $subscription->update([
                    'plan_id' => $plan->id,
                ]);
                $this->dispatch('alert', 'success', 'Suscripción actualizada correctamente');
            } else {
                $newSubscription = $this->user->newSubscription($plan->stripe_product_name, $priceId);
                if ($plan->free_trial_days) {
                    $newSubscription = $newSubscription->trialDays($plan->free_trial_days);
                }
                $subscription = $newSubscription->withMetadata([
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->title,
                ])->create($this->user->defaultPaymentMethod()->id);
                $subscription->update([
                    'plan_id' => $plan->id,
                ]);
                $this->user->notify(new SubscriptionCreate($subscription));
                $this->dispatch('alert', 'success', 'Suscripción creada correctamente');
            }
        } catch (IncompletePayment $exception) {
            return Redirect::route('cashier.payment', [$exception->payment->id, 'redirect' => route('admin.subscription.billing.index')]);
        } catch (Exception $e) {
            $this->dispatch('alert', 'error', 'Error al crear la suscripción, Intente con otro método de pago: '.$e->getMessage());
        }
    }
    public function cancelSubscription($planId) {
        $plan = Plan::find($planId);
        if (! $plan) {
            $this->dispatch('alert', 'error', 'El plan seleccionado no es válido');

            return;
        }
        try {
            $this->user->subscription($plan->stripe_product_name)->cancel();
            $this->dispatch('alert', 'success', 'Suscripción cancelada');
        } catch (Exception $e) {
            $this->dispatch('alert', 'error', 'Error al cancelar la suscripción, por favor contacte a soporte: '.$e->getMessage());
        }
    }
    public function resumeSubscription($planId) {
        $plan = Plan::find($planId);
        if (! $plan) {
            $this->dispatch('alert', 'error', 'El plan seleccionado no es válido');

            return;
        }
        try {
            $this->user->subscription($plan->stripe_product_name)->resume();
            $this->dispatch('alert', 'success', 'Suscripción renudada correctamente');
        } catch (Exception $e) {
            $this->dispatch('alert', 'error', 'Error al reanudar la suscripción, por favor contacte a soporte: '.$e->getMessage());
        }
    }

    // PAYMENT METHODS
    public function loadSetupIntent() {
        $setupIntent = $this->user->createSetupIntent();
        $this->stripeIntent = $setupIntent->client_secret ?? null;
    }
    public function addPaymentMethod($paymentMethodId) {
        if (! $this->user->hasStripeId()) {
            $this->user->createAsStripeCustomer();
            $this->user = $this->user->refresh();
        }
        $this->user->addPaymentMethod($paymentMethodId);
        if (! $this->user->hasDefaultPaymentMethod()) {
            $this->user->updateDefaultPaymentMethod($paymentMethodId);
        }
        $this->dispatch('alert', 'success', 'Método de pago agregado correctamente');

        // Si había un plan pendiente (el usuario hizo click en Suscribirte antes de agregar tarjeta),
        // creamos la suscripción automáticamente usando el plan almacenado.
        if ($this->pendingPlanId) {
            $planId = $this->pendingPlanId;
            $this->pendingPlanId = null;
            // Llamamos a newSubscription para crear la suscripción usando el método de pago recién agregado
            $this->newSubscription($planId);
        }
    }
    public function deletePaymentMethod($paymentMethodId) {
        $this->user->deletePaymentMethod($paymentMethodId);
        $this->dispatch('alert', 'success', 'Método de pago agregado eliminado correctamente');
    }
    public function defaultPaymentMethod($paymentMethodId) {
        $this->user->updateDefaultPaymentMethod($paymentMethodId);
        $this->dispatch('alert', 'success', 'Método de pago agregado como default');
    }

    // INVOICES
    public function downloadInvoice($invoiceId) {
        $pdf = $this->user->downloadInvoice($invoiceId, [
            'vendor' => config('app.name'),
            'product' => 'Suscripción a '.config('app.name'),
        ]);

        // Devolvemos el archivo como descarga
        return Response::streamDownload(function () use ($pdf) {
            echo $pdf;
        }, "invoice_{$invoiceId}.pdf");
    }

    // TOOLS
    public function loadPlanType() {
        $subscription = $this->user->subscriptions()->first();
        if ($subscription) {
            $planMonthSusbcribed = Plan::where('stripe_price_month_id', $subscription->stripe_price)->first();
            $planYearSusbcribed = Plan::where('stripe_price_year_id', $subscription->stripe_price)->first();
            if ($planMonthSusbcribed) {
                $this->planType = 'month';
            }
            if ($planYearSusbcribed) {
                $this->planType = 'year';
            }
        }
    }
}
