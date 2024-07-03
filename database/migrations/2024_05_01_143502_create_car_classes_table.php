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
        Schema::create('car_classes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the foreign key constraint from 'orders' table
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'car_class_id')) {
                $table->dropForeign(['car_class_id']); // Drop the foreign key constraint
                $table->dropColumn('car_class_id'); // Optionally drop the column if necessary
            }
        });

        // Now drop the 'car_classes' table
        Schema::dropIfExists('car_classes');
    }
};
