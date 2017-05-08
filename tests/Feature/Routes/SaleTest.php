<?php

namespace Tests\Feature\Routes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Sale;
use App\Models\Salesman;
use App\Transformers\SaleTransformer;

class SaleTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->salesman = factory(Salesman::class)->create();
        $this->user = factory(User::class)->create([
            'userable_id' => $this->salesman->id,
            'userable_type' => 'salesman'
        ]);
        $this->sales = factory(Sale::class, 2)->create();
    }

    /**
     * Test /api/vendas index route.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->json('GET', '/api/vendas', ['vendedor' => $this->user->id  ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                 'data' => [
                    '*' => [
                        'id', 'name', 'email', 'amount', 'commission', 'date'
                    ]
                 ]
             ])
            ->assertJsonFragment([
                'id' => $this->sales[0]->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]);
    }

    /**
     * Test /api/vendas index route for missing required params.
     *
     * @return void
     */
    public function testIndexValidation()
    {
        $response = $this->json('GET', '/api/vendas');

        $response->assertStatus(422);
    }
}
