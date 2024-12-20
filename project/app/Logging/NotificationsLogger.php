<?php

namespace App\Logging;

use App\Logging\Enum\LogChannels;

class NotificationsLogger extends AbstractLogger
{

    protected function getChannel(): LogChannels
    {
        return LogChannels::Notifications;
    }
}
