<?php

/**
 * MIGRACIÓN OPCIONAL
 * 
 * Implementa esta migración si deseas que los usuarios puedan controlar
 * qué tipos de notificaciones reciben por email/whatsapp.
 * 
 * Copiar a database/migrations/ si decides implementar esta funcionalidad.
 */

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
        Schema::create('user_notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Canales de notificación
            $table->boolean('email_enabled')->default(true);
            $table->boolean('whatsapp_enabled')->default(true);
            
            // Autenticación de dos factores
            $table->boolean('two_factor_enabled')->default(false);
            $table->enum('two_factor_method', ['email', 'whatsapp'])->default('email');
            
            // Tipos de notificación
            $table->boolean('notify_course_updates')->default(true);  // Actualizaciones de cursos
            $table->boolean('notify_enrollments')->default(true);     // Inscripciones
            $table->boolean('notify_comments')->default(true);        // Comentarios/respuestas
            $table->boolean('notify_quizzes')->default(true);         // Quizzes y resultados
            $table->boolean('notify_progress')->default(true);        // Progreso y logros
            $table->boolean('notify_community')->default(true);       // Comunidad/mensajes
            
            $table->timestamps();
            
            // Índices
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notification_preferences');
    }
};