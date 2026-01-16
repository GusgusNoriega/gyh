<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Configuración para correos destino de leads (permite múltiples correos separados por coma o punto y coma)
        $key = 'leads_notification_emails';

        $exists = DB::table('smtp_settings')->where('key', $key)->exists();

        if (! $exists) {
            DB::table('smtp_settings')->insert([
                'key' => $key,
                'value' => '',
                'description' => 'Correos destino para notificación de leads (separados por coma o punto y coma). Ej: ventas@dominio.com, gerente@dominio.com',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('smtp_settings')->where('key', 'leads_notification_emails')->delete();
    }
};

