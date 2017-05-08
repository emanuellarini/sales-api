<?php

namespace App\Repositories\Salesman;
use \Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class CachingSalesmanRepository implements SalesmanRepository
{
    private $repository;
    private $cache;
    private $key;

    public function __construct(SalesmanRepository $repository, CacheRepository $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
        $this->key = 'salesmen';
    }

    public function getAllWithUser()
    {
        return $this->cache->remember($this->key, 30, function () {
            return $this->repository->getAllWithUser();
        });
    }

    public function createWithUser($data)
    {
        $this->cache->forget($this->key);

        return $this->repository->createWithUser($data);
    }
}
