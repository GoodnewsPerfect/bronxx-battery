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
            $table->string('kc_user_id')->nullable()->unique()->after('id');
            $table->text('access_token')->nullable()->after('kc_user_id');
            $table->string('username')->nullable()->unique()->after('access_token');
            $table->string('phone_number')->nullable()->after('email');
            $table->string('profile_photo_url')->nullable()->after('phone_number');
            $table->string('password')->nullable()->change();
            $table->enum('auth_type', ['kingschat', 'regular'])->default('regular')->after('password');
            $table->string('country_code')->nullable()->after('auth_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'kc_user_id',
                'access_token',
                'username',
                'phone_number',
                'profile_photo_url',
                'auth_type',
                'country_code',
            ]);
            $table->string('password')->nullable(false)->change();
        });
    }
};
