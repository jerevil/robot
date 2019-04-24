<?php declare(strict_types=1);

namespace App\Providers;

use App\Services\Robot\Actions\Illuminate\ActionManager;
use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ActionManager::class, function ($app) {
            return new ActionManager($app);
        });
    }
}
