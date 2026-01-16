<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FileCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'code' => 'kickoff',
                'name' => 'Kickoff',
                'description' => 'Materiales de inicio del proyecto (brief, acta, alcance).',
                'is_system' => true,
                'sort_order' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'credentials',
                'name' => 'Credentials',
                'description' => 'Accesos y credenciales relacionadas al proyecto o tareas.',
                'is_system' => true,
                'sort_order' => 20,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'general',
                'name' => 'General',
                'description' => 'Archivos y enlaces generales del proyecto.',
                'is_system' => false,
                'sort_order' => 30,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('file_categories')->upsert(
            $rows,
            ['code'],
            ['name', 'description', 'is_system', 'sort_order', 'updated_at']
        );
    }
}