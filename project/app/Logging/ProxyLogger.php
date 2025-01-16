<?php

namespace App\Logging;

use App\Logging\Enum\LogChannels;

class ProxyLogger extends AbstractLogger
{
    protected function getChannel(): LogChannels
    {
        return LogChannels::Proxy;
    }
}
