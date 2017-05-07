<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Sale;
use App\Models\Salesman;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Salesman::class, 5)
            ->create()
            ->each(function ($salesman) {
                factory(User::class)->create([
                    'userable_id' => $salesman->id,
                    'userable_type' => Salesman::class
                ]);
            });
        factory(Sale::class, 10)->create();
    }
}
