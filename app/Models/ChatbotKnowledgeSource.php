<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ChatbotKnowledgeSource extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    public function chatbot() {
        return $this->belongsTo(Chatbot::class);
    }
    public function pathToString() {
        switch ($this->type) {
            case 'file':
                return Storage::url($this->path);
            case 'url':
                return $this->path;
            default:
                return $this->path;
        }
    }
    public function getStatusToString() {
        switch ($this->status) {
            case 'pending':
                return __('Pendig');
            case 'processing':
                return __('Processing');
            case 'completed':
                return __('Completed');
            case 'failed':
                return __('Failed');
            default:
                return __('Unknown');
        }
    }
}
