<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SendMessageViaTelegramBotNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private ?string $message)
    {
    }

    public function via($notifiable)
    {
        return [
            'telegram',
        ];
    }

    public function toTelegram($notifiable)
    {
        return $this->message;
    }
}
