<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('baseline_start')->nullable();
            $table->date('baseline_end')->nullable();
            $table->date('start_planned')->nullable();
            $table->date('end_planned')->nullable();
            $table->date('start_actual')->nullable();
            $table->date('end_actual')->nullable();
            $table->unsignedTinyInteger('progress')->default(0);
            $table->string('color', 32)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['start_planned', 'end_planned']);
        });

        Schema::create('task_status', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->string('color', 32)->nullable();
            $table->boolean('is_closed')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('tasks')->nullOnDelete();
            $table->foreignId('status_id')->nullable()->constrained('task_status')->nullOnDelete();

            $table->string('code', 50)->nullable();
            $table->string('wbs_code', 50)->nullable();
            $table->string('wbs_path', 255)->nullable();

            $table->string('title', 255);
            $table->text('description')->nullable();

            $table->smallInteger('priority')->default(0);
            $table->integer('order_index')->default(0);

            $table->date('start_planned')->nullable();
            $table->date('end_planned')->nullable();
            $table->date('start_actual')->nullable();
            $table->date('end_actual')->nullable();

            $table->decimal('estimate_hours', 8, 2)->nullable();
            $table->decimal('spent_hours', 8, 2)->nullable();
            $table->unsignedTinyInteger('progress')->default(0);

            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['project_id', 'code']);
            $table->index(['project_id', 'start_planned']);
            $table->index(['project_id', 'end_planned']);
            $table->index(['parent_id', 'order_index']);
            $table->index('status_id');
            $table->index('assignee_id');
            $table->index('wbs_path');
        });

        Schema::create('task_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('predecessor_task_id')->constrained('tasks')->cascadeOnDelete();
            $table->foreignId('successor_task_id')->constrained('tasks')->cascadeOnDelete();
            $table->enum('type', ['FS', 'SS', 'FF', 'SF'])->default('FS');
            $table->integer('lag_minutes')->default(0);
            $table->timestamps();

            $table->unique(['predecessor_task_id', 'successor_task_id'], 'uniq_task_dependency_pair');
            $table->index(['project_id', 'successor_task_id'], 'idx_proj_successor');
        });

        Schema::create('file_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('media_assetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_asset_id')->constrained('media_assets')->cascadeOnDelete();
            $table->string('attachable_type');
            $table->unsignedBigInteger('attachable_id');
            $table->foreignId('file_category_id')->nullable()->constrained('file_categories')->nullOnDelete();
            $table->string('title', 255)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['attachable_type', 'attachable_id'], 'idx_attachable');
            $table->index(['media_asset_id', 'file_category_id'], 'idx_media_category');
            $table->unique(['media_asset_id', 'attachable_type', 'attachable_id', 'file_category_id'], 'uniq_asset_attach_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_assetables');
        Schema::dropIfExists('file_categories');
        Schema::dropIfExists('task_dependencies');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('task_status');
        Schema::dropIfExists('projects');
    }
};