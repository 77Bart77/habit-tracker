<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoalCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('goal_categories')->insert([
            [
                'name'       => 'Zdrowie',
                'color'      => '#22c55e', // zielony
                'user_id'    => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Nauka / Rozwój',
                'color'      => '#3b82f6', // niebieski
                'user_id'    => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Praca / Kariera',
                'color'      => '#eab308', // żółty
                'user_id'    => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Finanse',
                'color'      => '#f97316', // pomarańcz
                'user_id'    => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Relacje / Rodzina',
                'color'      => '#ec4899', // róż
                'user_id'    => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Samopoczucie / Psychika',
                'color'      => '#a855f7', // fiolet
                'user_id'    => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Dom / Organizacja',
                'color'      => '#0ea5e9', // jasny niebieski
                'user_id'    => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Hobby / Pasja',
                'color'      => '#f97316', 
                'user_id'    => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Sport / Aktywność',
                'color'      => '#16a34a',
                'user_id'    => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Inne',
                'color'      => '#64748b', // neutralny
                'user_id'    => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
