<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as m;
use App\Repositories\Sale\CachingSaleRepository;
use App\Repositories\Sale\SaleRepository;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Sale;

class SaleRepositoryTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    private $repo;
    private $cache;
    private $user;
    private $sales;
    public function setUp()
    {
        parent::setUp();
        cache()->flush();
        $this->user = factory(User::class)->states('salesman')->create();
        $this->sales = factory(Sale::class, 2)->create();

        $this->repo = m::mock(SaleRepository::class);
        $this->cache = m::mock(CacheRepository::class);
    }

    /**
     * Test fetching all items by user id
     *
     * @return void
     */
    public function testFetchingByUser()
    {
        $id = $this->user->id;

        $this->repo->shouldReceive('findByUser')->with($id)->andReturn($this->sales);

        $this->cache->shouldReceive('tags')->with('sales')->andReturn($this->cache);
        $this->cache->shouldReceive('remember')->andReturn($this->sales);

        $cacheRepo = new CachingSaleRepository($this->repo, $this->cache);

        // Get all
        $items = $cacheRepo->findByUser($id);

        $this->assertEquals($this->sales, $items);
    }

    /**
     * Test creating a single item
     *
     * @return void
     */
    public function testCreating()
    {
        $data = ['user_id' => $this->user->id, 'amount' => 100000];
        $sale = factory(Sale::class)->create($data);

        $this->repo->shouldReceive('create')->with($data)->andReturn($sale);

        $this->cache->shouldReceive('tags')->with('sales')->andReturn($this->cache);
        $this->cache->shouldReceive('forget')->andReturn(null);

        $repository = new CachingSaleRepository($this->repo, $this->cache);

        $item = $repository->create($data);

        $this->assertEquals($item, $sale);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }
}
