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
            $table->string('graduation_year', 191)->nullable();
            $table->boolean('is_child_chair')->default(false);
            $table->date('insurance_policy_date')->nullable();
            $table->string('insurance_policy_image')->nullable();
            $table->date('tech_passport_date')->nullable();
            $table->string('tech_passport_image')->nullable();
        });

        Schema::table('drivers', function (Blueprint $table) {
            $table->string('avatar_image')->nullable();
            $table->string('ipn_number', 191)->nullable();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->string('avatar_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn('avatar_image');
            $table->dropColumn('ipn_number');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('avatar_image');
        });

        Schema::table('taxi', function (Blueprint $table) {
            $table->dropColumn('graduation_year', 191);
            $table->dropColumn('is_child_chair');
            $table->dropColumn('insurance_policy_date');
            $table->dropColumn('insurance_policy_image');
            $table->dropColumn('tech_passport_date');
            $table->dropColumn('tech_passport_image');
        });
    }
};
