<?php

namespace App\Logging;

use App\Logging\Enum\LogChannels;

class AuthLogger extends AbstractLogger
{

    protected function getChannel(): LogChannels
    {
        return LogChannels::Auth;
    }
}
