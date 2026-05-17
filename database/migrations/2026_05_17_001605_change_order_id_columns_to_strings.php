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
        $this->changeOrderIdColumnToString('order_items', 'order_items_order_id_foreign');
        $this->changeOrderIdColumnToString('transaction_logs', 'transaction_logs_order_id_foreign');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->changeOrderIdColumnToBigInteger('transaction_logs', 'transaction_logs_order_id_foreign');
        $this->changeOrderIdColumnToBigInteger('order_items', 'order_items_order_id_foreign');
    }

    private function changeOrderIdColumnToString(string $tableName, string $foreignKeyName): void
    {
        if (! Schema::hasTable($tableName) || ! Schema::hasColumn($tableName, 'order_id')) {
            return;
        }

        if ($this->orderIdIsString($tableName)) {
            return;
        }

        $this->dropForeignKey($tableName, $foreignKeyName);

        Schema::table($tableName, function (Blueprint $table) {
            $table->string('order_id')->change();
        });

        $this->ensureOrderIdIndex($tableName);
    }

    private function changeOrderIdColumnToBigInteger(string $tableName, string $foreignKeyName): void
    {
        if (! Schema::hasTable($tableName) || ! Schema::hasColumn($tableName, 'order_id')) {
            return;
        }

        if (! $this->orderIdIsString($tableName)) {
            return;
        }

        $this->dropIndex($tableName, "{$tableName}_order_id_index");

        Schema::table($tableName, function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->change();
        });

        Schema::table($tableName, function (Blueprint $table) use ($foreignKeyName) {
            $table->foreign('order_id', $foreignKeyName)
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete();
        });
    }

    private function orderIdIsString(string $tableName): bool
    {
        return in_array(Schema::getColumnType($tableName, 'order_id'), ['string', 'varchar'], true);
    }

    private function dropForeignKey(string $tableName, string $foreignKeyName): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($foreignKeyName) {
            $table->dropForeign($foreignKeyName);
        });
    }

    private function ensureOrderIdIndex(string $tableName): void
    {
        if ($this->orderIdIndexExists($tableName)) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) {
            $table->index('order_id');
        });
    }

    private function dropIndex(string $tableName, string $indexName): void
    {
        if (! $this->indexExists($tableName, $indexName)) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($indexName) {
            $table->dropIndex($indexName);
        });
    }

    private function indexExists(string $tableName, string $indexName): bool
    {
        return collect(Schema::getIndexes($tableName))
            ->contains(fn (array $index) => ($index['name'] ?? null) === $indexName);
    }

    private function orderIdIndexExists(string $tableName): bool
    {
        return collect(Schema::getIndexes($tableName))
            ->contains(fn (array $index) => ($index['columns'] ?? []) === ['order_id']);
    }
};
