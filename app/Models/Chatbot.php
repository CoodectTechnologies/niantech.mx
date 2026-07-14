<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Chatbot extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function chatbotKnowledgeSources() {
        return $this->hasMany(ChatbotKnowledgeSource::class);
    }
    public function chatbotChats() {
        return $this->hasMany(ChatbotChat::class);
    }
    public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function getSystemPrompt() {
        $systemPromt = $this->system_promt;
        $systemPromt .= '\n\n';
        $systemPromt .= $this->chatbotKnowledgeSources->pluck('extracted_content')->join('\n\n');

        return $systemPromt;
    }
    public function imagePreview() {
        $image = asset('assets/admin/media/svg/files/blank-image.svg');
        if ($this->image) {
            if (Storage::exists($this->image)) {
                $image = Storage::url($this->image);
            } else {
                $image = $this->image;
            }
        }

        return $image;
    }
}
