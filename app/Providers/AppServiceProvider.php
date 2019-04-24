<?php

namespace App\Providers;

use App\Models\Repositories\BlockStateRepository;
use App\Models\Repositories\Contracts\BlockStateRepository as BlockStateRepositoryContract;
use App\Services\Robot\Contracts\ManipulateService as ManipulateServiceContract;
use App\Services\Robot\Contracts\RobotService as RobotServiceContract;
use App\Services\Robot\ManipulateService;
use App\Services\Robot\RobotService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BlockStateRepositoryContract::class, BlockStateRepository::class);

        $this->app->bind(RobotServiceContract::class, RobotService::class);
        $this->app->bind(ManipulateServiceContract::class, ManipulateService::class);
    }
}
