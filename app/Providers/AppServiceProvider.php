<?php

namespace App\Providers;

use App\Contracts\ShopeeServiceContract;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Shopee\ShopeeService;
use App\Services\Storage\User\UserFileInterface;
use App\Services\Storage\User\UserFiles;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    if ($this->app->environment('local')) {
      $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
      $this->app->register(TelescopeServiceProvider::class);
    }
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    $this->app->bind(ShopeeServiceContract::class, ShopeeService::class);
    $this->app->bind(UserFileInterface::class, UserFiles::class);
  }
}
