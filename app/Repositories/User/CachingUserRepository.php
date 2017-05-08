<?php

namespace App\Repositories\User;
use \Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use App\Models\User;
use App\Models\Sale;

class CachingUserRepository implements UserRepository
{
    private $repository;
    private $cache;
    private $key;

    public function __construct(UserRepository $repository, CacheRepository $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
        $this->key = 'users';
    }

    public function findById($id)
    {
        $key = $this->key . '.' . $id;
        return $this->cache->remember($key, 30, function ()  use ($id) {
            return $this->repository->findById($id);
        });
    }

    public function storeUserableCommission(User $user, Sale $sale)
    {
        return $this->repository->storeUserableCommission($user, $sale);
    }
}
