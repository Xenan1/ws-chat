<?php

namespace App\Logging;

use App\Logging\Enum\LogChannels;

class ParsingLogger extends AbstractLogger
{

    protected function getChannel(): LogChannels
    {
        return LogChannels::Parsing;
    }
}
