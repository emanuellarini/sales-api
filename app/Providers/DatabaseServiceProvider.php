<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Salesman;
use App\Repositories\Salesman\SalesmanRepository;
use App\Repositories\Salesman\EloquentSalesmanRepository;
use App\Repositories\Salesman\CachingSalesmanRepository;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'salesman' => Salesman::class,
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SalesmanRepository::class, function () {
            $baseRepo = new EloquentSalesmanRepository;
            $cacheRepo = $this->app['cache.store'];
            return new CachingSalesmanRepository($baseRepo, $cacheRepo);
        });
    }
}
