<?php

namespace App\Repositories\Sale;
use \Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class CachingSaleRepository implements SaleRepository
{
    private $repository;
    private $cache;
    private $key;

    public function __construct(SaleRepository $repository, CacheRepository $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
        $this->key = 'sales';
    }

    public function findByUser($id)
    {
        return $this->cache->remember($this->key, 30, function () use ($id) {
            return $this->repository->findByUser($id);
        });
    }

    public function create($data)
    {
        $this->cache->forget($this->key);
        $this->cache->forget('users');
        $this->cache->forget('salesmen');

        return $this->repository->create($data);
    }
}
