<?php

namespace App\Logging\Enum;

enum LogChannels: string
{
    case Auth = 'auth';
    case Chat = 'chat';
    case Notifications = 'notifications';
    case Cache = 'cache';
    case Parsing = 'parsing';
    case Proxy = 'proxy';
}
