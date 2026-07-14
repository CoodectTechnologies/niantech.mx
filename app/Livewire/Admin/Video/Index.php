<?php

namespace App\Livewire\Admin\Video;

use App\Models\Video;
use Exception;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];

    public function render() {
        $videos = Video::orderByDesc('id')->get();

        return view('livewire.admin.video.index', compact('videos'));
    }
    public function destroy(Video $video) {
        try {
            if (Storage::exists($video->video)) {
                Storage::delete($video->video);
            }
            $video->delete();
            Video::regenerateCache();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
