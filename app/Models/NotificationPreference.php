<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'push_notifications' => 'boolean',
        'email_notifications' => 'boolean',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
