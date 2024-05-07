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
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('phone')->unique();
            $table->string('email')->unique(); // Email (unique)
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
        Schema::dropIfExists('drivers');

        Schema::table('orders', function (Blueprint $table) {
            // Drop the foreign key and the driver_id column
            $table->dropForeign(['driver_id']);
            $table->dropColumn('driver_id');
        });
    }
};
