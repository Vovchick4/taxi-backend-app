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
        Schema::table('orders', function (Blueprint $table) {
            $table->double('total_price');
            $table->double('distance');
            $table->string('start_street_name');
            $table->string('end_street_name');
            // Add start and end coordinates using GEOMETRY type
            $table->point('start_location')->nullable();
            $table->point('end_location')->nullable();

            // Add the car_class_id column
            $table->unsignedBigInteger('car_class_id')->nullable();

            // Add a foreign key constraint
            $table->foreign('car_class_id')
                ->references('id')
                ->on('car_classes')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the added columns
            $table->dropColumn('total_price');
            $table->dropColumn('distance');
            $table->dropColumn('start_street_name');
            $table->dropColumn('end_street_name');
            $table->dropColumn('start_location');
            $table->dropColumn('end_location');
        });
    }
};
