<?php

namespace App\Logging\Enum;

enum LogChannels: string
{
    case Auth = 'auth';
    case Chat = 'chat';
}
