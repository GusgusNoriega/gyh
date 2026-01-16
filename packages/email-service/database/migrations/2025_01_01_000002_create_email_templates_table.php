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
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // Nombre descriptivo de la plantilla
            $table->string('key')->unique();                 // Clave única para identificar la plantilla (ej: welcome_email)
            $table->string('subject');                       // Asunto del email (puede contener shortcodes)
            $table->longText('content_html');                // Contenido HTML del email (puede contener shortcodes)
            $table->json('variables_schema')->nullable();    // Schema de variables: {"nombre": {"required": true, "description": "..."}}
            $table->string('description')->nullable();       // Descripción de uso de la plantilla
            $table->boolean('is_active')->default(true);     // Si la plantilla está activa
            $table->timestamps();
            $table->softDeletes();                           // Soft delete para recuperación
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};