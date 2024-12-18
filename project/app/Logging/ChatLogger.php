<?php

namespace App\Logging;

use App\Logging\Enum\LogChannels;

class ChatLogger extends AbstractLogger
{

    protected function getChannel(): LogChannels
    {
        return LogChannels::Chat;
    }
}
