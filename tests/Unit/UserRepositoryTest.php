<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as m;
use App\Repositories\User\CachingUserRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Sale;
use App\Models\Salesman;

class UserRepositoryTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    private $repo;
    private $cache;
    private $user;
    public function setUp()
    {
        parent::setUp();
        cache()->flush();
        $this->user = factory(User::class)->states('salesman')->create();

        $this->repo = m::mock(UserRepository::class);
        $this->cache = m::mock(CacheRepository::class);
    }

    /**
     * Test fetching one item
     *
     * @return void
     */
    public function testFetchingById()
    {
        // Create mock of decorated repository
        $this->repo->shouldReceive('findById')->with(1)->andReturn($this->user);

        // Create mock of cache
        $this->cache->shouldReceive('tags')->with('users.1')->andReturn($this->cache);
        $this->cache->shouldReceive('remember')->andReturn($this->user);

        // Instantiate the repository
        $cacheRepo = new CachingUserRepository($this->repo, $this->cache);

        // Get all
        $item = $cacheRepo->findById(1);

        $this->assertEquals($this->user, $item);
    }

    /**
     * Test creating a single item
     *
     * @return void
     */
    public function testStoreUserableCommission()
    {
        $data = ['user_id' => 1, 'amount' => 200000];
        $sale = factory(Sale::class)->create($data);
        $shouldReturn = [
            'id' => $sale->id,
            'name' => $sale->user->name,
            'email' => $sale->user->email,
            'amount' => 'R$ ' . number_format($sale->amount , 2, ',', '.'),
            'commission' => 'R$ ' . number_format($sale->commission, 2, ',', '.'),
            'date' => \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y H:i:s'),
        ];
        $this->repo->shouldReceive('storeUserableCommission')->with($this->user, $sale)->andReturn($shouldReturn);

        $this->cache->shouldReceive('tags')->with('salesmen')->andReturn($this->cache);
        $this->cache->shouldReceive('forget')->andReturn(true);

        $repository = new CachingUserRepository($this->repo, $this->cache);

        $item = $repository->storeUserableCommission($this->user, $sale);

        $this->assertEquals($item, $shouldReturn);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }
}
