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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            // Datos del contacto
            $table->string('name')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable()->index();

            // Datos de empresa (opcional)
            $table->boolean('is_company')->default(false)->index();
            $table->string('company_name')->nullable();
            $table->string('company_ruc')->nullable()->index();

            // Datos del proyecto
            $table->string('project_type')->nullable()->index();
            $table->unsignedInteger('budget_up_to')->nullable()->index();
            $table->text('message')->nullable();

            // Gestión interna
            $table->string('status')->default('new')->index(); // new|contacted|qualified|lost|won
            $table->text('notes')->nullable();

            // Metadatos (origen / tracking)
            $table->string('source')->nullable()->index();
            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};

