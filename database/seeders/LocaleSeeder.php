<?php

namespace Database\Seeders;

use Pieldefoca\Lux\Models\Locale;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Locale::create([
            'code' => 'es',
            'name' => 'Español',
            'flag' => 'es.svg',
            'default' => true,
        ]);

        Locale::create([
            'code' => 'eu',
            'name' => 'Euskera',
            'flag' => 'eu.svg',
            'default' => false,
        ]);
    }
}
