<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('quote_item_tasks') || !Schema::hasColumn('quote_item_tasks', 'description')) {
            return;
        }

        // Cambiar a LONGTEXT para permitir descripciones extensas.
        // Evitamos depender de doctrine/dbal usando SQL directo.
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `quote_item_tasks` MODIFY `description` LONGTEXT NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE quote_item_tasks ALTER COLUMN description TYPE TEXT');
            DB::statement('ALTER TABLE quote_item_tasks ALTER COLUMN description DROP NOT NULL');
        } else {
            // Fallback: en SQLite/otros drivers, TEXT ya soporta tamaños grandes.
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('quote_item_tasks') || !Schema::hasColumn('quote_item_tasks', 'description')) {
            return;
        }

        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `quote_item_tasks` MODIFY `description` TEXT NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE quote_item_tasks ALTER COLUMN description TYPE TEXT');
            DB::statement('ALTER TABLE quote_item_tasks ALTER COLUMN description DROP NOT NULL');
        } else {
            // No-op
        }
    }
};

