<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update 'drivers' table
        if (Schema::hasTable('drivers')) {
            Schema::table('drivers', function (Blueprint $table) {
                // Conditionally add columns if they do not exist
                if (!Schema::hasColumn('drivers', 'city')) {
                    $table->string('city', 191);
                }
                if (!Schema::hasColumn('drivers', 'passport_image')) {
                    $table->string('passport_image');
                }
                if (!Schema::hasColumn('drivers', 'passport_expiration_date')) {
                    $table->date('passport_expiration_date')->nullable();
                }
            });
        }

        // Update 'clients' table
        if (Schema::hasTable('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                // Conditionally add columns if they do not exist
                if (!Schema::hasColumn('clients', 'city')) {
                    $table->string('city', 191);
                }
                if (!Schema::hasColumn('clients', 'surname')) {
                    $table->string('surname', 191);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse changes in 'drivers' table
        if (Schema::hasTable('drivers')) {
            Schema::table('drivers', function (Blueprint $table) {
                // Drop the columns if they exist
                if (Schema::hasColumn('drivers', 'city')) {
                    $table->dropColumn('city');
                }
                if (Schema::hasColumn('drivers', 'passport_image')) {
                    $table->dropColumn('passport_image');
                }
                if (Schema::hasColumn('drivers', 'passport_expiration_date')) {
                    $table->dropColumn('passport_expiration_date');
                }
            });
        }

        // Reverse changes in 'clients' table
        if (Schema::hasTable('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                // Drop the columns if they exist
                if (Schema::hasColumn('clients', 'city')) {
                    $table->dropColumn('city');
                }
                if (Schema::hasColumn('clients', 'surname')) {
                    $table->dropColumn('surname');
                }
            });
        }
    }
};
