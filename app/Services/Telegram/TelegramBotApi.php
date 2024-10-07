<?php

namespace App\Services\Telegram;

use Illuminate\Support\Facades\Http;

class TelegramBotApi
{

    public const HOST = 'https://api.telegram.org/bot';

    public static function sendMessage($token, $chatId, string $message = ''): void
    {
        Http::get(self::HOST . $token . '/sendMessage', [
            'chat_id' => $chatId,
            'text' => $message,
        ]);
    }

}
