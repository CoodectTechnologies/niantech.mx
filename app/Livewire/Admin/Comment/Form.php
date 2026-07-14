<?php

namespace App\Livewire\Admin\Comment;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Form extends Component
{
    public $model;
    public $comment;
    public $method;
    public $userPresent;

    public function mount($model, Comment $comment, $method) {
        $this->model = $model;
        $this->comment = $comment;
        $this->method = $method;
        $this->userPresent = Auth::user();
    }
    protected function rules() {
        return [
            'comment.stars' => 'required',
            'comment.name' => 'required',
            'comment.body' => 'required',
        ];
    }
    public function render() {
        return view('livewire.admin.comment.form');
    }
    public function store() {
        $this->validate();
        $this->comment->approved = true;
        $this->model->comments()->create($this->comment->toArray());
        $this->comment = new Comment;
        $this->dispatch('alert', 'success', 'Creación con éxito');
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->comment->update();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
}
