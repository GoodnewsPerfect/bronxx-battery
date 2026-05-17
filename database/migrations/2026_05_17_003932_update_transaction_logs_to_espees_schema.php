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
        if (! Schema::hasTable('transaction_logs')) {
            if (DB::getDriverName() === 'mysql') {
                DB::statement(<<<'SQL'
CREATE TABLE `transaction_logs` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(255) NOT NULL,
  `transaction_id` text NOT NULL,
  `user_id` text NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8
SQL);

                return;
            }

            Schema::create('transaction_logs', function (Blueprint $table) {
                $table->increments('id');
                $table->string('order_id');
                $table->text('transaction_id');
                $table->text('user_id');
                $table->timestamp('datetime')->useCurrent();
                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->timestamp('updated_at')->nullable()->useCurrent();
                $table->string('status', 100)->nullable();
            });

            return;
        }

        $this->dropForeignKey('transaction_logs', 'transaction_logs_order_id_foreign');
        $this->dropForeignKey('transaction_logs', 'transaction_logs_user_id_foreign');
        $this->dropIndex('transaction_logs', 'transaction_logs_order_id_transaction_id_index');
        $this->dropIndex('transaction_logs', 'transaction_logs_user_id_foreign');
        $this->dropIndexesForColumns('transaction_logs', ['user_id']);
        $this->dropIndexesForColumns('transaction_logs', ['transaction_id']);

        if (! Schema::hasColumn('transaction_logs', 'datetime')) {
            Schema::table('transaction_logs', function (Blueprint $table) {
                $table->timestamp('datetime')->useCurrent()->after('user_id');
            });
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `transaction_logs` MODIFY `id` INT(20) NOT NULL AUTO_INCREMENT');
            DB::statement('ALTER TABLE `transaction_logs` MODIFY `order_id` VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE `transaction_logs` MODIFY `transaction_id` TEXT NOT NULL');
            DB::statement('ALTER TABLE `transaction_logs` MODIFY `user_id` TEXT NOT NULL');
            DB::statement('ALTER TABLE `transaction_logs` MODIFY `datetime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
            DB::statement('ALTER TABLE `transaction_logs` MODIFY `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP');
            DB::statement('ALTER TABLE `transaction_logs` MODIFY `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP');
            DB::statement('ALTER TABLE `transaction_logs` MODIFY `status` VARCHAR(100) NULL DEFAULT NULL');
            DB::statement('ALTER TABLE `transaction_logs` ENGINE=InnoDB DEFAULT CHARSET=utf8');

            return;
        }

        Schema::table('transaction_logs', function (Blueprint $table) {
            $table->string('order_id')->change();
            $table->text('transaction_id')->change();
            $table->text('user_id')->change();
            $table->timestamp('created_at')->nullable()->useCurrent()->change();
            $table->timestamp('updated_at')->nullable()->useCurrent()->change();
            $table->string('status', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('transaction_logs')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `transaction_logs` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
            DB::statement('ALTER TABLE `transaction_logs` MODIFY `transaction_id` VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE `transaction_logs` MODIFY `user_id` BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE `transaction_logs` MODIFY `status` VARCHAR(255) NOT NULL DEFAULT "initiated"');
        }

        if (Schema::hasColumn('transaction_logs', 'datetime')) {
            Schema::table('transaction_logs', function (Blueprint $table) {
                $table->dropColumn('datetime');
            });
        }
    }

    private function dropForeignKey(string $tableName, string $foreignKeyName): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        if (! $this->foreignKeyExists($tableName, $foreignKeyName)) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($foreignKeyName) {
            $table->dropForeign($foreignKeyName);
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

    private function foreignKeyExists(string $tableName, string $foreignKeyName): bool
    {
        if (DB::getDriverName() !== 'mysql') {
            return true;
        }

        return DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $tableName)
            ->where('CONSTRAINT_NAME', $foreignKeyName)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->exists();
    }

    private function indexExists(string $tableName, string $indexName): bool
    {
        return collect(Schema::getIndexes($tableName))
            ->contains(fn (array $index) => ($index['name'] ?? null) === $indexName);
    }

    private function dropIndexesForColumns(string $tableName, array $columns): void
    {
        collect(Schema::getIndexes($tableName))
            ->filter(fn (array $index) => ($index['columns'] ?? []) === $columns)
            ->reject(fn (array $index) => ($index['primary'] ?? false) === true)
            ->each(function (array $index) use ($tableName) {
                $indexName = $index['name'] ?? null;

                if (! $indexName) {
                    return;
                }

                Schema::table($tableName, function (Blueprint $table) use ($indexName) {
                    $table->dropIndex($indexName);
                });
            });
    }
};
