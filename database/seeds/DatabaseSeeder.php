<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Sale;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 5)->states('salesman')->create();
        factory(Sale::class, 10)->create();
    }
}
