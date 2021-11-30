<?php

use App\Models\User;
use Illuminate\Support\Str;

if (! function_exists('bot_config')) {
    function bot_config()
    {
        return config('bot');
    }
}

if (! function_exists('token_config')) {
    function token_config()
    {
        return bot_config()['token'];
    }
}

if (! function_exists('token_length')) {
    function token_length()
    {
        return token_config()['length'];
    }
}

if (! function_exists('my_telegram_token')) {
    function my_telegram_token()
    {
        return auth()->user()->telegram_token;
    }
}

if (! function_exists('generate_unique_telegram_token')) {
    function generate_unique_telegram_token(): string
    {
        $telegram_token = Str::random(token_length());
        $token_used = User::query()
            ->where('telegram_token', $telegram_token)
            ->exists();

        if ($token_used) {
            return generate_unique_telegram_token();
        }

        return $telegram_token;
    }
}
