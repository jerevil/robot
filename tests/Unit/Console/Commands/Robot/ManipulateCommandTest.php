<?php declare(strict_types=1);

namespace Test\Unit\Console\Commands\Robot;

use App\Models\BlockState;
use App\Models\Repositories\Contracts\BlockStateRepository;
use App\Services\Robot\Contracts\RobotService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ManipulateCommandTest extends TestCase
{
    /** @var MockInterface */
    private $blockStateRepository;

    /** @var MockInterface */
    private $robotService;

    public function setUp(): void
    {
        parent::setUp();

        $this->blockStateRepository = Mockery::mock(BlockStateRepository::class);
        $this->app->instance(BlockStateRepository::class, $this->blockStateRepository);

        $this->robotService= Mockery::mock(RobotService::class);
        $this->app->instance(RobotService::class, $this->robotService);
    }

    public function test_can_init()
    {
        $action = 'move';
        $position = 'over';
        $movingBlock = 1;
        $receivingBlock = 3;
        $blocks = [0 => [0, 1], 1 => [], 2 => [2, 3, 4], 3 => [], 4 => []];
        $blockState = factory(BlockState::class)->make();

        $this->blockStateRepository
            ->shouldReceive('findLastUnfinished')
            ->once()
            ->andReturn($blockState);

        $this->blockStateRepository
            ->shouldReceive('updateBlock')
            ->once()
            ->with($blockState, $blocks);

        $this->robotService
            ->shouldReceive('manipulate')
            ->once()
            ->with($action, $position, $movingBlock, $receivingBlock, $blockState->blocks)
            ->andReturn($blocks);

        $this->artisan("robot:manipulate $action $movingBlock $position $receivingBlock");
    }

    public function test_can_not_manipulate_when_not_init()
    {
        $action = 'move';
        $position = 'over';
        $movingBlock = 1;
        $receivingBlock = 3;

        $this->blockStateRepository
            ->shouldReceive('findLastUnfinished')
            ->once()
            ->andThrow(ModelNotFoundException::class);

        $this->blockStateRepository->shouldNotReceive('updateBlock');
        $this->robotService->shouldNotReceive('manipulate');

        $this->artisan("robot:manipulate $action $movingBlock $position $receivingBlock");
    }

    public function test_can_not_manipulate_when_robot_service_thrown_exception()
    {
        $action = 'move';
        $position = 'over';
        $movingBlock = 1;
        $receivingBlock = 3;
        $blockState = factory(BlockState::class)->make();

        $this->blockStateRepository
            ->shouldReceive('findLastUnfinished')
            ->once()
            ->andReturn($blockState);

        $this->blockStateRepository->shouldNotReceive('updateBlock');

        $this->robotService
            ->shouldReceive('manipulate')
            ->once()
            ->with($action, $position, $movingBlock, $receivingBlock, $blockState->blocks)
            ->andThrow(Exception::class);

        $this->artisan("robot:manipulate $action $movingBlock $position $receivingBlock");
    }
}
