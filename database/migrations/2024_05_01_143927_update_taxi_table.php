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
        Schema::table('taxi', function (Blueprint $table) {
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
        Schema::table('taxi', function (Blueprint $table) {
            // Remove the foreign key constraint
            $table->dropForeign(['car_class_id']);

            // Drop the car_class_id column
            $table->dropColumn('car_class_id');
        });
    }
};
