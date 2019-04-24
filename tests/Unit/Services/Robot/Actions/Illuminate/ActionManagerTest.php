<?php declare(strict_types=1);

namespace Tests\Unit\Services\Robot\Actions\Illuminate;

use App\Exceptions\UnknownActionException;
use App\Services\Robot\Actions\Illuminate\ActionManager;
use App\Services\Robot\Actions\MoveAction;
use App\Services\Robot\Actions\PileAction;
use Illuminate\Contracts\Container\BindingResolutionException;
use Tests\TestCase;

class ActionManagerTest extends TestCase
{
    /** @var ActionManager */
    private $manager;

    public function setUp(): void
    {
        parent::setUp();

        $this->manager = $this->app->make(ActionManager::class);
    }

    public function test_can_not_get_default_driver()
    {
        $this->expectException(UnknownActionException::class);
        $this->manager->getDefaultDriver();
    }

    public function test_can_create_move_driver()
    {
        $this->assertInstanceOf(MoveAction::class, $this->manager->createMoveDriver());
    }

    public function test_can_create_pile_driver()
    {
        $this->assertInstanceOf(PileAction::class, $this->manager->createPileDriver());
    }
}
