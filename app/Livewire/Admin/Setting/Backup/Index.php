<?php

namespace App\Livewire\Admin\Setting\Backup;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Index extends Component
{
    public function render() {
        $backups = collect(Storage::files(config('backup.backup.name')))->sortDesc();

        return view('livewire.admin.setting.backup.index', compact('backups'));
    }
    public function generate() {
        Artisan::call('backup:run --only-db');
        $this->dispatch('alert', 'success', 'Copia de seguridad creada con éxito');
    }
    public function download($name) {
        return Storage::download($name);
    }
    public function destroy($name) {
        try {
            Storage::delete($name);
            $this->dispatch('alert', 'success', 'Eliminación con exito');
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
