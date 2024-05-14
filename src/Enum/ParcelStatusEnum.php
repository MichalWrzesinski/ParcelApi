<?php

declare(strict_types=1);

namespace App\Enum;

enum ParcelStatusEnum: string
{
    case PREPARING = 'preparing';
    case SHIPPED = 'shipped';
    case DELIVERY_RECEIVED = 'delivery_received';
    case IN_TRANSIT = 'in_transit';
    case OUT_FOR_DELIVERY = 'out_for_delivery';
    case AWAITING_PICKUP = 'awaiting_pickup';
    case DELIVERED = 'delivered';
}
