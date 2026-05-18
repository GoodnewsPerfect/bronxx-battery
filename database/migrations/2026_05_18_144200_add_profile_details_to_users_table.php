<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->unique()->after('email');
            }

            if (! Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('email');
            }

            if (! Schema::hasColumn('users', 'birth_date')) {
                $afterColumn = Schema::hasColumn('users', 'phone_number') ? 'phone_number' : 'email';
                $table->date('birth_date')->nullable()->after($afterColumn);
            }

            if (! Schema::hasColumn('users', 'country')) {
                $afterColumn = Schema::hasColumn('users', 'country_code')
                    ? 'country_code'
                    : (Schema::hasColumn('users', 'phone_number') ? 'phone_number' : 'email');
                $table->string('country')->nullable()->after($afterColumn);
            }

            if (! Schema::hasColumn('users', 'state')) {
                $table->string('state')->nullable()->after('country');
            }

            if (! Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('state');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['birth_date', 'country', 'state', 'city'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
