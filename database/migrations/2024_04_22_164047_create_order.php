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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->enum('status', [OrderStatus::Active->value, OrderStatus::FindedDriver->value, OrderStatus::Finished->value, OrderStatus::InTheWay->value, OrderStatus::CanceledByDriver->value, OrderStatus::CanceledByClient->value]);
            $table->enum('payment_status', [OrderPaymentStatus::Paid->value, OrderPaymentStatus::NotPaid->value]);

            $table->string('payment_method');
            $table->timestamps();

            // Add the client_id foreign key
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
