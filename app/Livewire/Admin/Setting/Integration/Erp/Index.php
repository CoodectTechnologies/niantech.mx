<?php

namespace App\Livewire\Admin\Setting\Integration\Erp;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Throwable;

class Index extends Component
{
    protected $listeners = ['render'];

    public function render() {
        $canSync = config('services.odoo.status') && config('services.odoo.url') && config('services.odoo.language') && config('services.odoo.key') && config('services.odoo.database');
        return view('livewire.admin.setting.integration.erp.index', compact('canSync'));
    }
    public function sync(){
        try{
            Artisan::call('odoo:save');
            $this->dispatch('alert', 'success', 'Sincronización éxitosa');
        }catch(Throwable $e){    
            $this->dispatch('alert', 'error', 'Ocurrio un error: '. $e->getMessage());
        }
    }
}
