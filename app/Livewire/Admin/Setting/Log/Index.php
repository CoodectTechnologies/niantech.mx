<?php

namespace App\Livewire\Admin\Setting\Log;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $user;

    public function mount($user = null) {
        if ($user) {
            $this->user = $user;
        }
    }
    public function render() {
        if ($this->user) {
            $logs = $this->user->actions()->orderBy('id', 'desc');
        } else {
            $logs = Activity::with('causer')->orderBy('id', 'desc');
        }
        $logs = $logs->paginate(20);

        return view('livewire.admin.setting.log.index', compact('logs'));
    }
    public function placeholder(array $params = []) {
        return view('admin.components.skeletons.general', $params);
    }
    public function optimizeDatabase() {
        Artisan::call('activitylog:clean');
        $this->dispatch('alert', 'success', 'Base de datos optimizadas');
    }
}
