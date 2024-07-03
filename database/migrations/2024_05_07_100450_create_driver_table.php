<?php

use App\Enums\ClientRole;
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
        Schema::create('drivers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable(); // Email (unique)
            $table->timestamp('verified_at')->nullable();
            $table->enum('role', [ClientRole::Client->value, ClientRole::Driver->value]);
            $table->rememberToken();
            $table->timestamps();

            // Add a new unsigned big integer column for the driver_id
            $table->unsignedBigInteger('taxi_id')->nullable();
            // Add the foreign key relationship
            $table->foreign('taxi_id')
                ->references('id')
                ->on('taxi');
        });

        Schema::table('orders', function (Blueprint $table) {
            // Add a new unsigned big integer column for the driver_id
            $table->unsignedBigInteger('driver_id')->nullable();

            // Add the foreign key relationship
            $table->foreign('driver_id')
                ->references('id')
                ->on('drivers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key from 'orders' table before dropping 'drivers' table
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'driver_id')) {
                $table->dropForeign(['driver_id']); // Drop the foreign key constraint
                $table->dropColumn('driver_id'); // Drop the column if necessary
            }
        });

        // Now drop the 'drivers' table
        Schema::dropIfExists('drivers');
    }
};
