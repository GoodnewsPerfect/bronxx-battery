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
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('transaction_id');
            }

            if (! Schema::hasColumn('orders', 'amount_paid')) {
                $table->decimal('amount_paid', 12, 2)->nullable()->after('payment_id');
            }

            if (! Schema::hasColumn('orders', 'currency')) {
                $table->string('currency')->nullable()->after('amount_paid');
            }

            if (! Schema::hasColumn('orders', 'transact_time')) {
                $table->timestamp('transact_time')->nullable()->after('currency');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'transact_time')) {
                $table->dropColumn('transact_time');
            }

            if (Schema::hasColumn('orders', 'currency')) {
                $table->dropColumn('currency');
            }

            if (Schema::hasColumn('orders', 'amount_paid')) {
                $table->dropColumn('amount_paid');
            }

            if (Schema::hasColumn('orders', 'payment_id')) {
                $table->dropColumn('payment_id');
            }
        });
    }
};
