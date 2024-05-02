<?php

namespace App\Enums;

// Define the OrderPaymentStatus enum
enum OrderPaymentStatus: string
{
    case Paid = 'paid';
    case NotPaid = 'no_paid';
}
