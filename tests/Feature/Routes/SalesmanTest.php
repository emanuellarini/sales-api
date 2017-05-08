<?php

namespace Tests\Feature\Routes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Sale;
use App\Models\Salesman;
use App\Transformers\SalesmanTransformer;
use League\Fractal\Resource\Item;

class SalesmanTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * Test /api/vendores index route.
     *
     * @return void
     */
    public function testIndex()
    {
        $salesman = factory(Salesman::class)->create();
        factory(User::class)->create([
            'userable_id' => $salesman->id,
            'userable_type' => 'salesman'
        ]);

        $response = $this->json('GET', '/api/vendedores');

        $response->assertStatus(200)
            ->assertJsonStructure([
                 'data' => [
                    '*' => [
                        'id', 'name', 'email', 'commission'
                    ]
                 ]
             ])
            ->assertJsonFragment([
                'name' => $salesman->user->name,
                'email' => $salesman->user->email,
            ]);
    }

    /**
     * Test /api/vendores index route.
     *
     * @return void
     */
    public function testStore()
    {
        $data = ['name' => 'John Doe', 'email' => 'john_doe@test.com.br'];
        $response = $this->json('POST', 'api/vendedores', $data);

        $this->assertDatabaseHas('users', $data);

        $response->assertStatus(200)
            ->assertJson([ 'data' => $data ]);
    }
}
