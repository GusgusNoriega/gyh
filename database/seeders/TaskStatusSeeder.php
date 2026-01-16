<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'code' => 'planned',
                'name' => 'Planned',
                'color' => '#64748b',
                'is_closed' => false,
                'sort_order' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'in_progress',
                'name' => 'In Progress',
                'color' => '#0ea5e9',
                'is_closed' => false,
                'sort_order' => 20,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'blocked',
                'name' => 'Blocked',
                'color' => '#ef4444',
                'is_closed' => false,
                'sort_order' => 30,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'done',
                'name' => 'Done',
                'color' => '#22c55e',
                'is_closed' => true,
                'sort_order' => 90,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'canceled',
                'name' => 'Canceled',
                'color' => '#a1a1aa',
                'is_closed' => true,
                'sort_order' => 100,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        TaskStatus::upsert(
            $rows,
            ['code'],
            ['name', 'color', 'is_closed', 'sort_order', 'updated_at']
        );
    }
}