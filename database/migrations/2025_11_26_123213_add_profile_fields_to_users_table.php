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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('default_currency', 3)->default('TRY')->after('phone');
            $table->string('status', 20)->default('active')->after('default_currency');
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->timestamp('profile_completed_at')->nullable()->after('last_login_at');
            $table->json('preferences')->nullable()->after('profile_completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'default_currency',
                'status',
                'last_login_at',
                'profile_completed_at',
                'preferences',
            ]);
        });
    }
};
