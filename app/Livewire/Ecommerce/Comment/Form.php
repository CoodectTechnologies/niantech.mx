<?php

namespace App\Livewire\Ecommerce\Comment;

use App\Models\Comment;
use App\Models\User;
use App\Notifications\Comment\CommentCreate as NotificationCommentCreate;
use App\Traits\LivewireRecaptcha;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Lukeraymonddowning\Honey\Traits\WithRecaptcha;

class Form extends Component
{
    use LivewireRecaptcha;
    use WithRecaptcha;

    public $model;
    public $comment;

    public function mount($model, Comment $comment) {
        $this->model = $model;
        $this->comment = $comment;
        $this->comment->name = Auth::check() ? Auth::user()->name : '';
        $this->comment->email = Auth::check() ? Auth::user()->email : '';
    }
    protected function rules() {
        return [
            'comment.name' => 'required',
            'comment.email' => 'required|email',
            'comment.stars' => 'required|min:1|max:5',
            'comment.body' => 'required',
        ];
    }
    public function render() {
        return view('livewire.ecommerce.comment.form');
    }
    public function store() {
        $this->validateRecaptcha();
        $this->validate();
        $this->comment->user_id = Auth::id() ?? null;
        $this->comment->approved = false;
        $this->comment = $this->model->comments()->create($this->comment->toArray());
        $this->notifyUsers();
        $this->comment = new Comment;
        session()->flash('alert-comment-type', 'success');
        session()->flash('alert-comment', __('The comment has been sent, for security reasons it will be reviewed before being published'));
    }
    private function notifyUsers() {
        Notification::send(
            User::permission(['comentarios'])->get(),
            new NotificationCommentCreate($this->model, $this->comment)
        );
    }
}
