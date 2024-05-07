<?php

namespace App\Enums;

// Define the OrderStatus enum
enum ClientRole: string
{
    case Client = 'client';
    case Driver = 'driver';
    public function description(): string
    {
        return match ($this) {
            self::Client => 'client role!',
            self::Driver => 'driver role!',
        };
    }
}
