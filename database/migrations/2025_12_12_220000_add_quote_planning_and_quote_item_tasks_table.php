<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Planning settings for timeline calculations
        Schema::table('quote_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('quote_settings', 'work_hours_per_day')) {
                $table->decimal('work_hours_per_day', 5, 2)->default(8)->after('default_tax_rate');
            }
        });

        // Optional start date for the estimate (timeline)
        Schema::table('quotes', function (Blueprint $table) {
            if (!Schema::hasColumn('quotes', 'estimated_start_date')) {
                $table->date('estimated_start_date')->nullable()->after('valid_until');
            }
        });

        // Tasks per quote item (breakdown)
        Schema::create('quote_item_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quote_item_id');
            $table->string('name');
            // Puede ser texto largo (detalles extensos por tarea)
            $table->longText('description')->nullable();
            $table->decimal('duration_value', 10, 2)->default(0);
            $table->enum('duration_unit', ['hours', 'days'])->default('hours');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('quote_item_id')->references('id')->on('quote_items')->cascadeOnDelete();
            $table->index(['quote_item_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_item_tasks');

        Schema::table('quotes', function (Blueprint $table) {
            if (Schema::hasColumn('quotes', 'estimated_start_date')) {
                $table->dropColumn('estimated_start_date');
            }
        });

        Schema::table('quote_settings', function (Blueprint $table) {
            if (Schema::hasColumn('quote_settings', 'work_hours_per_day')) {
                $table->dropColumn('work_hours_per_day');
            }
        });
    }
};

