<?php

namespace App\Livewire\Admin\Test;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Superbyte23\SileoLivewire\Concerns\HasSileoToasts;

class Index extends Component
{
    use HasSileoToasts;

    public function mount() {}
    public function render() {
        return view('livewire.admin.test.index');
    }
    public function old() {
        $this->dispatch('alert', 'success', 'Registro exitoso', 'Todo bien my bro, aqui todo tranquilos karnal sjsjsjhahaajs');
    }
    public function reload() {
        Session::flash('alert', 'Saved your register, exist my bro!');
        Session::flash('alert-description', 'Todo con éxito papuh');
        Session::flash('alert-type', 'success');

        return Redirect::route('admin.test.index');
    }
    public function success() {
        $this->toastSuccess('Saved your register, exist my bro!', 'Your changes were persisted.', 99999999999);
    }
    public function error(): void {
        $this->toastError('Saved your register, exist my bro!', 'Your changes were persisted.', 99999999999);
    }
    public function warning(): void {
        $this->toastWarning('Saved your register, exist my bro!', 'Your changes were persisted.', 99999999999);
    }
    public function info(): void {
        $this->toastInfo('Saved your register, exist my bro!', 'Your changes were persisted.', 99999999999);
    }
    public function action() {
        $this->toastAction(
            type: 'info',
            title: 'Item deleted',
            description: 'The record has been removed.',
            actionLabel: 'Undo',
            actionEvent: 'undo-delete',   // Livewire event dispatched on click
            actionParams: [42],            // optional params
        );
    }
    public function custom($position) {
        // top-left top-center top-right bottom-left bottom-center bottom-right
        $this->dispatch('sileo', type: 'success', title: 'Done!', position: $position, description: 'The record has been removed.');
    }
}
