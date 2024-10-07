<?php

namespace App\Logging\Telegram;

use App\Services\Telegram\TelegramBotApi;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;

class TelegramLoggerHandler extends AbstractProcessingHandler
{

    public array $config = [];

    public function __construct(array $config)
    {
        $level = Logger::toMonologLevel($config['level']);
        $this->config = $config;

        parent::__construct($level);
    }

    protected function write(LogRecord $record): void
    {
        TelegramBotApi::sendMessage(
            $this->config['token'],
            $this->config['chat_id'],
            $record['formatted'],
        );
    }
}
