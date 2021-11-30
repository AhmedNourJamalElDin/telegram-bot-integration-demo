<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Chat extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'chat_id',
        'username',
    ];

    public function scopeMyChats($query)
    {
        return $query->where('user_id', auth()->id());
    }

    public function routeNotificationForTelegram()
    {
        return $this->chat_id;
    }
}
