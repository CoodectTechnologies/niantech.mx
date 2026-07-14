<?php

namespace App\Livewire\Admin\Video;

use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $video;
    public $method;
    public $videoTmp;

    protected function rules() {
        return [
            'video.platform' => 'required',
            'video.iframe' => in_array($this->video->platform, [Video::PLATFORM_YOUTUBE, Video::PLATFORM_VIMEO]) ? 'required' : 'nullable',
            'videoTmp' => in_array($this->video->platform, [Video::PLATFORM_LOCAL]) ? 'required' : 'nullable',
        ];
    }
    public function mount(Video $video, $method) {
        $this->video = $video;
        $this->method = $method;
    }
    public function render() {
        return view('livewire.admin.video.form');
    }
    public function store() {
        $this->validate();
        $this->_saveIframe();
        $this->video->save();
        $this->video = new Video;
        $this->regenerateCache();
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->_saveIframe();
        $this->video->update();
        $this->regenerateCache();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
    public function removeVideo() {
        if ($this->video->video) {
            if (Storage::exists($this->video->video)) {
                Storage::delete($this->video->video);
            }
            $this->video->video = null;
            $this->video->update();
        }
        $this->reset('videoTmp');
        $this->dispatch('alert', 'success', __('Video successfully deleted'));
    }
    private function _saveIframe() {
        $platform = $this->video->platform;
        if (! in_array($platform, [Video::PLATFORM_YOUTUBE, Video::PLATFORM_VIMEO])) {
            $this->video->iframe = '';
        }
    }
    private function regenerateCache() {
        Video::regenerateCache();
    }
}
