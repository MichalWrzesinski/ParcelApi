<?php

declare(strict_types=1);

namespace App\Enum;

enum EmployeePositionEnum: string
{
    case MANAGER = 'manager';
    case DELIVERER = 'deliverer';
    case WAREHOUSE_WORKER = 'warehouse_worker';
}
