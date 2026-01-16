<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RbacSeeder::class,
            DefaultUserSeeder::class,
            CurrencySeeder::class,
            PassportKeysSeeder::class,
            CleanUploadsSeeder::class,
            UserSeeder::class,
            ColorThemeSeeder::class,
            TaskStatusSeeder::class,
            FileCategorySeeder::class,
            // Cotización de ejemplo (web estándar) para DECORYGNACIO (PEN, IGV 18%)
            DecorygnacioStandardWebsiteQuoteSeeder::class,
            // Cotización de ejemplo (web profesional) para DECORYGNACIO (PEN, IGV 18%)
            DecorygnacioProfessionalWebsiteQuoteSeeder::class,
            // Cotización de ejemplo: Sistema de Cotizaciones y Facturación con SUNAT (PEN, sin IGV) - Selmag SAC
            SelmagSacQuoteSeeder::class,
        ]);
    }
}
