<?php

declare(strict_types=1);

namespace App\Enum;

enum NotificationStatusEnum: string
{
    case NEW = 'new';
    case READ = 'read';
}
