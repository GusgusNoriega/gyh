<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('smtp_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insertar configuraciones SMTP por defecto
        DB::table('smtp_settings')->insert([
            [
                'key' => 'smtp_host',
                'value' => '',
                'description' => 'Servidor SMTP (por ejemplo: smtp.gmail.com)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'smtp_port',
                'value' => '587',
                'description' => 'Puerto SMTP (por ejemplo: 587, 465)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'smtp_encryption',
                'value' => 'tls',
                'description' => 'Tipo de encriptación (tls, ssl o null)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'smtp_username',
                'value' => '',
                'description' => 'Usuario/correo de autenticación SMTP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'smtp_password',
                'value' => '',
                'description' => 'Contraseña o password de la cuenta SMTP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'smtp_from_address',
                'value' => '',
                'description' => 'Correo remitente por defecto (from address)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'smtp_from_name',
                'value' => 'Mi Aplicación',
                'description' => 'Nombre visible del remitente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smtp_settings');
    }
};