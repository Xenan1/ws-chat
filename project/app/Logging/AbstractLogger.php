<?php

namespace App\Logging;

use App\Logging\Enum\LogChannels;
use App\Logging\Enum\LogLevels;
use Illuminate\Support\Facades\Log;

abstract class AbstractLogger
{
    abstract protected function getChannel(): LogChannels;

    final public function log(LogLevels $level, string $message, array $context = []): void
    {
        Log::channel($this->getChannel()->value)->log($level->value, $message, $context);
    }
}
