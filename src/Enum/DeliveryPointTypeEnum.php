<?php

declare(strict_types=1);

namespace App\Enum;

enum DeliveryPointTypeEnum: string
{
    case SHOP = 'shop';
    case BOX = 'box';
    case GAS_STATION = 'gas_station';
}
