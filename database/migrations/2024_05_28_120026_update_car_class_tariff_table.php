<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('car_classes', function (Blueprint $table) {
            $table->string('tariff_name');
            $table->double('tariff_price');
            $table->double('tariff_discount_price')->nullable();
            $table->boolean('is_discount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_classes', function (Blueprint $table) {
            $table->dropColumn('tariff_name');
            $table->dropColumn('tariff_price');
            $table->dropColumn('tariff_discount_price');
            $table->dropColumn('is_discount');
        });
    }
};
