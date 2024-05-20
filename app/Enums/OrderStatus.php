<?php

namespace App\Enums;

// Define the OrderStatus enum
enum OrderStatus: string
{
    case New = 'new';
    case Active = 'active';
    case FindedDriver = 'finded_driver';
    case InTheWay = 'in_the_way';
    case Finished = 'finished';
    case CanceledByDriver = 'canceled_by_driver';
    case CanceledByClient = 'canceled_by_client';

    public function color(): string
    {
        return match ($this) {
            self::New => 'yellow',
            self::Active => 'green',
            self::FindedDriver => 'green',
            self::InTheWay => 'green',
            self::Finished => 'gray',
            self::CanceledByDriver => 'red',
            self::CanceledByClient => 'red',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::New => 'New!',
            self::Active => 'Active!',
            self::FindedDriver => 'Finded driver!',
            self::InTheWay => 'In the way!',
            self::Finished => 'Order finished!',
            self::CanceledByDriver => 'Oh, driver canceled order!',
            self::CanceledByClient => 'Oh, order canceled by client!',
        };
    }
}
