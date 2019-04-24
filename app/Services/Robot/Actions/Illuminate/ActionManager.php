<?php declare(strict_types=1);

namespace App\Services\Robot\Actions\Illuminate;

use App\Exceptions\UnknownActionException;
use App\Services\Robot\Actions\Contracts\ActionService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Manager;

class ActionManager extends Manager
{
    /**
     * @return string|void
     *
     * @throws UnknownActionException
     */
    public function getDefaultDriver()
    {
        throw new UnknownActionException('default');
    }

    /**
     * @return ActionService
     * @throws BindingResolutionException
     */
    public function createMoveDriver()
    {
        $config = $this->app['config']['services.actions.move'];
        return $this->app->make($config['class']);
    }

    /**
     * @return ActionService
     * @throws BindingResolutionException
     */
    public function createPileDriver()
    {
        $config = $this->app['config']['services.actions.pile'];
        return $this->app->make($config['class']);
    }
}
