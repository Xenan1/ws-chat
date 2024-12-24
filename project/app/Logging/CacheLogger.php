<?php

namespace App\Logging;

use App\Logging\Enum\LogChannels;

class CacheLogger extends AbstractLogger
{
    protected function getChannel(): LogChannels
    {
        return LogChannels::Cache;
    }
}
