<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\OrderStatus;
use App\Enums\OrderPaymentStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status',  [
                OrderStatus::New->value,
                OrderStatus::Active->value,
                OrderStatus::FindedDriver->value,
                OrderStatus::Finished->value,
                OrderStatus::InTheWay->value,
                OrderStatus::CanceledByDriver->value,
                OrderStatus::CanceledByClient->value
            ])->default(OrderStatus::New->value)->change();
            $table->enum('payment_status', [OrderPaymentStatus::Paid->value, OrderPaymentStatus::NotPaid->value])->default(OrderPaymentStatus::NotPaid->value)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [OrderStatus::Active->value, OrderStatus::FindedDriver->value, OrderStatus::Finished->value, OrderStatus::InTheWay->value, OrderStatus::CanceledByDriver->value, OrderStatus::CanceledByClient->value])->change();
            $table->enum('payment_status', [OrderPaymentStatus::Paid->value, OrderPaymentStatus::NotPaid->value])->change();
        });
    }
};
