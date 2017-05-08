<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as m;
use App\Repositories\Salesman\CachingSalesmanRepository;
use App\Repositories\Salesman\SalesmanRepository;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Sale;
use App\Models\Salesman;

class SalesmanRepositoryTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    private $repo;
    private $cache;
    private $salemen;
    public function setUp()
    {
        parent::setUp();
        cache()->flush();
        $this->salemen = factory(User::class, 3)->states('salesman')->create();

        $this->repo = m::mock(SalesmanRepository::class);
        $this->cache = m::mock(CacheRepository::class);
    }

    /**
     * Test fetching all items
     *
     * @return void
     */
    public function testFetchingAllWithUser()
    {
        // Create mock of decorated repository
        $this->repo->shouldReceive('getAllWithUser')->andReturn($this->salemen);

        // Create mock of cache
        $this->cache->shouldReceive('tags')->with('salesmen')->andReturn($this->cache);
        $this->cache->shouldReceive('remember')->andReturn($this->salemen);

        // Instantiate the repository
        $cacheRepo = new CachingSalesmanRepository($this->repo, $this->cache);

        // Get all
        $items = $cacheRepo->getAllWithUser();

        $this->assertCount(3, $items);
    }

    /**
     * Test creating a single item
     *
     * @return void
     */
    public function testCreatingWithUser()
    {
        $data = ['name' => 'John Doe', 'email' => 'john_doe@test.com.br'];

        $this->repo->shouldReceive('createWithUser')->with($data)->andReturn($data);

        $this->cache->shouldReceive('tags')->with('salesmen')->andReturn($this->cache);
        $this->cache->shouldReceive('forget')->andReturn(true);

        $repository = new CachingSalesmanRepository($this->repo, $this->cache);

        $item = $repository->createWithUser($data);

        $this->assertEquals($item, $data);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }
}
