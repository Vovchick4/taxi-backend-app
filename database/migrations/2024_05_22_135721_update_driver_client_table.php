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
        Schema::table('clients', function (Blueprint $table) {
            $table->enum('role', [ClientRole::Client->value, ClientRole::Driver->value])->default(ClientRole::Client->value);
        });

        Schema::table('drivers', function (Blueprint $table) {
            $table->enum('role', [ClientRole::Client->value, ClientRole::Driver->value])->default(ClientRole::Driver->value)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('drivers', function (Blueprint $table) {
            $table->enum('role', [ClientRole::Client->value, ClientRole::Driver->value])->change();
        });
    }
};
