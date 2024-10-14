<?php

namespace Database\Seeders;

use App\Models\Packs;
use Illuminate\Database\Seeder;

class PacksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Example data for packs
        $packs = [
            ['name' => '250 widgets', 'count_of_widgets' => 250],
            ['name' => '500 widgets', 'count_of_widgets' => 500],
            ['name' => '1000 widgets', 'count_of_widgets' => 1000],
            ['name' => '2000 widgets', 'count_of_widgets' => 2000],
            ['name' => '5000 widgets', 'count_of_widgets' => 5000],

        ];

        // Inserting each pack into the 'packs' table
        foreach ($packs as $pack) {
            Packs::create($pack);
        }
    }
}
