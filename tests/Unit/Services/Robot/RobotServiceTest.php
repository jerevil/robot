<?php declare(strict_types=1);

namespace Tests\Unit\Services\Robot;

use App\Exceptions\ManipulateException;
use App\Services\Robot\Actions\Contracts\ActionService;
use App\Services\Robot\Actions\Illuminate\ActionManager;
use App\Services\Robot\Contracts\RobotService;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class RobotServiceTest extends TestCase
{
    /** @var RobotService */
    private $service;

    /** @var MockInterface */
    private $actionManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->actionManager = Mockery::mock(ActionManager::class);
        $this->app->instance(ActionManager::class, $this->actionManager);

        $this->service = $this->app->make(RobotService::class);
    }

    public function test_can_manipulate()
    {
        $blocks = $this->getBlocks();
        $action = 'move';
        $position = 'over';
        $movingBlock = 1;
        $receivingBlock = 2;

        $actionService = Mockery::mock(ActionService::class);
        $this->actionManager->shouldReceive('driver')->once()->with($action)->andReturn($actionService);

        $actionService
            ->shouldReceive('manipulate')
            ->once()
            ->with($position, $movingBlock, $receivingBlock, $blocks)
            ->andReturn($blocks);

        $this->assertEquals(
            $blocks,
            $this->service->manipulate($action, $position, $movingBlock, $receivingBlock, $blocks)
        );
    }

    public function test_can_not_manipulate_when_same_block()
    {
        $blocks = $this->getBlocks();
        $action = 'move';
        $position = 'over';
        $movingBlock = 1;
        $receivingBlock = 1;

        $this->actionManager->shouldNotHaveBeenCalled();


        $this->expectException(ManipulateException::class);
        $this->expectExceptionMessage('The moving block and the receiving block should not be the same');
        $this->service->manipulate($action, $position, $movingBlock, $receivingBlock, $blocks);
    }

    public function test_can_not_manipulate_when_block_id_too_high()
    {
        $blocks = $this->getBlocks();
        $action = 'move';
        $position = 'over';
        $movingBlock = 10;
        $receivingBlock = 1;

        $this->actionManager->shouldNotHaveBeenCalled();


        $this->expectException(ManipulateException::class);
        $this->expectExceptionMessage('You selected an inexistent block');
        $this->service->manipulate($action, $position, $movingBlock, $receivingBlock, $blocks);
    }

    private function getBlocks(): array
    {
        return [
            0 => [0, 1],
            1 => [],
            2 => [2, 3, 4],
            3 => [],
            4 => [],
        ];
    }
}
