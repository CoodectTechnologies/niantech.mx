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

    public function mount(User $user){
        $this->user = $user;
        $this->loadPlanType();
    }
    public function render(){
        $plans = Plan::query()->where('status', true)->orderBy('order', 'asc')->get();
        $paymentMethods = $this->user->paymentMethods();
        $invoices = $this->user->invoices();
        $this->user = $this->user->refresh();
        return view('livewire.admin.subscription.billing.index', compact('plans', 'paymentMethods', 'invoices'));
    }
    // SUBSCRIPTIONS
    public function newSubscription($planId){
        $plan = Plan::find($planId);
        if(!$plan):
            $this->dispatch('alert', 'error', 'El plan seleccionado no es válido');
            return;
        endif;

        // Determinamos qué precio se va a usar según el tipo seleccionado (Mensual / Anual)
        $priceId = $this->planType === 'month' ? $plan->stripe_price_month_id : $plan->stripe_price_year_id;
        
        // ¿Es un plan gratuito?
        $isFreePlan = $plan->isFree();

        // Si NO es un plan gratis y NO tiene método de pago, abrimos el modal de la tarjeta
        if(!$isFreePlan && !$this->user->hasDefaultPaymentMethod()):
            // Guardamos el plan pendiente y pedimos al front que abra el modal de nuevo método
            $this->pendingPlanId = $plan->id;
            $this->dispatch('openPaymentModalForPlan', $plan->id);
            return;
        endif;

        try{
            // Validamos si el usuario ya está suscrito al producto en Stripe
            if($this->user->subscribed($plan->stripe_product_name)):
                $subscription = $this->user->subscription($plan->stripe_product_name);
                
                // CONDICIÓN 3: Impedir volver a registrarse en el gratis si ya tiene el gratis activo
                if ($isFreePlan && $subscription->plan_id == $plan->id && $subscription->active()) {
                    $this->dispatch('alert', 'info', 'Ya te encuentras registrado en el plan gratuito.');
                    return;
                }

                // CONDICIÓN 2: El usuario cambia de plan (Upgrade o Downgrade de pago a gratis)
                // Usamos swap() nativo de Cashier. Stripe se encarga de cambiar el precio (aunque sea a $0.00)
                $subscription->swap($priceId);
                $subscription->update([
                    'plan_id' => $plan->id
                ]);

                $this->user = $this->user->refresh();
                $this->dispatch('alert', 'success', 'Suscripción actualizada correctamente');
            else:
                // CONDICIÓN 1: Compra inicial / Registro por primera vez
                $newSubscription = $this->user->newSubscription($plan->stripe_product_name, $priceId);
                
                if($plan->free_trial_days):
                    $newSubscription = $newSubscription->trialDays($plan->free_trial_days);
                endif;

                $subscriptionBuilder = $newSubscription->withMetadata([
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->title
                ]);

                // Si es gratis, hacemos el .create() pasando null (sin tarjeta). Stripe lo acepta porque vale 0
                if ($isFreePlan) {
                    $subscription = $subscriptionBuilder->create(null);
                } else {
                    $subscription = $subscriptionBuilder->create($this->user->defaultPaymentMethod()->id);
                }

                $subscription->update([
                    'plan_id' => $plan->id,
                ]);

                $this->user = $this->user->refresh();
                $this->user->notify(new SubscriptionCreate($subscription));
                $this->dispatch('alert', 'success', 'Suscripción creada correctamente');
            endif;
        }catch(IncompletePayment $exception) {
            return Redirect::route('cashier.payment', [$exception->payment->id, 'redirect' => route('admin.subscription.billing.index')]);
        }catch(Exception $e){
            $this->dispatch('alert', 'error', 'Error al procesar la suscripción: '. $e->getMessage());
        }
    }
    public function cancelSubscription($planId){
        $plan = Plan::find($planId);
        if(!$plan):
            $this->dispatch('alert', 'error', 'El plan seleccionado no es válido');
            return;
        endif;
        try{
            $this->user->subscription($plan->stripe_product_name)->cancel();
            $this->dispatch('alert', 'success', 'Suscripción cancelada');
        }catch(Exception $e){
            $this->dispatch('alert', 'error', 'Error al cancelar la suscripción, por favor contacte a soporte: '. $e->getMessage());
        }
    }
    public function resumeSubscription($planId){
        $plan = Plan::find($planId);
        if(!$plan):
            $this->dispatch('alert', 'error', 'El plan seleccionado no es válido');
            return;
        endif;
        try{
            $this->user->subscription($plan->stripe_product_name)->resume();
            $this->dispatch('alert', 'success', 'Suscripción renudada correctamente');
        }catch(Exception $e){
            $this->dispatch('alert', 'error', 'Error al reanudar la suscripción, por favor contacte a soporte: '. $e->getMessage());
        }
    }
    // PAYMENT METHODS
    public function loadSetupIntent(){
        $setupIntent = $this->user->createSetupIntent();
        $this->stripeIntent = $setupIntent->client_secret ?? null;
    }
    public function addPaymentMethod($paymentMethodId){
        if(!$this->user->hasStripeId()):
            $this->user->createAsStripeCustomer();
            $this->user = $this->user->refresh();
        endif;
        $this->user->addPaymentMethod($paymentMethodId);   
        if(!$this->user->hasDefaultPaymentMethod()):
            $this->user->updateDefaultPaymentMethod($paymentMethodId);
        endif;
        $this->dispatch('alert', 'success', 'Método de pago agregado correctamente');

        // Si había un plan pendiente (el usuario hizo click en Suscribirte antes de agregar tarjeta),
        // creamos la suscripción automáticamente usando el plan almacenado.
        if($this->pendingPlanId){
            $planId = $this->pendingPlanId;
            $this->pendingPlanId = null;
            // Llamamos a newSubscription para crear la suscripción usando el método de pago recién agregado
            $this->newSubscription($planId);
        }
    } 
    public function deletePaymentMethod($paymentMethodId){
        $this->user->deletePaymentMethod($paymentMethodId);   
        $this->dispatch('alert', 'success', 'Método de pago agregado eliminado correctamente');
    } 
    public function defaultPaymentMethod($paymentMethodId){
        $this->user->updateDefaultPaymentMethod($paymentMethodId);   
        $this->dispatch('alert', 'success', 'Método de pago agregado como default');
    } 
    // INVOICES
    public function downloadInvoice($invoiceId){
        $pdf = $this->user->downloadInvoice($invoiceId, [
            'vendor'  => config('app.name'),
            'product' => 'Suscripción a ' . config('app.name'),
        ]);

        // Devolvemos el archivo como descarga
        return Response::streamDownload(function () use ($pdf) {
            echo $pdf;
        }, "invoice_{$invoiceId}.pdf");
    }
    // TOOLS
    public function loadPlanType(){
        $subscription = $this->user->subscriptions()->first();
        if($subscription):
            $planMonthSusbcribed = Plan::where('stripe_price_month_id', $subscription->stripe_price)->first();
            $planYearSusbcribed = Plan::where('stripe_price_year_id', $subscription->stripe_price)->first();
            if($planMonthSusbcribed):
                $this->planType = 'month';
            endif;
            if($planYearSusbcribed):
                $this->planType = 'year';
            endif;
        endif;
    }
}
