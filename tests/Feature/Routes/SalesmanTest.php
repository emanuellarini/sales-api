<?php

namespace Tests\Feature\Routes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;

class SalesmanTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        cache()->flush();
    }

    /**
     * Test /api/vendores index route.
     *
     * @return void
     */
    public function testIndex()
    {
        $user = factory(User::class)->states('salesman')->create();

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
                'name' => $user->name,
                'email' => $user->email,
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
