<?php

namespace App\Http\Controllers;

use App\Services\TelegramBotWebHookHandler;
use Telegram\Bot\Objects\Update as UpdateObject;

class TelegramBotWebHookController extends Controller
{
    public function __invoke(TelegramBotWebHookHandler $handler)
    {
        $updates = new UpdateObject(request()->all());

        $handler->handler($updates);

        return response()->json([]);
    }
}
