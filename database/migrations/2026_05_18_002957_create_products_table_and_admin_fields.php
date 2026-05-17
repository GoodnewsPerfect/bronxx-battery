<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(false)->after('auth_type');
            }
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('image')->nullable();
            $table->boolean('is_sold_out')->default(false);
            $table->timestamps();
        });

        DB::table('products')->insert([
            [
                'name' => 'Bronxx Core Battery',
                'description' => 'High-density lithium-polymer cell tuned...',
                'price' => '0.10',
                'image' => 'images/product1.jpg',
                'is_sold_out' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bronxx Fast Charger',
                'description' => 'USB-C fast charger with smart thermal...',
                'price' => '0.10',
                'image' => 'images/product2.jpg',
                'is_sold_out' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Battery Module Pro',
                'description' => 'Pro-grade module for extended runtime...',
                'price' => '0.10',
                'image' => 'images/product1.jpg',
                'is_sold_out' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thermal Shield Kit',
                'description' => 'Lightweight layer that improves heat...',
                'price' => '0.10',
                'image' => 'images/product2.jpg',
                'is_sold_out' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $adminEmail = env('ADMIN_EMAIL', 'admin@bronx.test');

        if (! DB::table('users')->where('email', $adminEmail)->exists()) {
            DB::table('users')->insert([
                'name' => env('ADMIN_NAME', 'Bronx Admin'),
                'email' => $adminEmail,
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
                'is_admin' => true,
                'auth_type' => 'regular',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_admin')) {
                $table->dropColumn('is_admin');
            }
        });
    }
};
