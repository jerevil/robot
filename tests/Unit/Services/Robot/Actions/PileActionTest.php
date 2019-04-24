<?php declare(strict_types=1);

namespace Tests\Unit\Services\Robot\Actions;

use App\Exceptions\ManipulateException;
use App\Exceptions\UnknownPositionException;
use App\Services\Robot\Actions\PileAction;
use App\Services\Robot\Contracts\ManipulateService;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class PileActionTest extends TestCase
{
    /** @var PileAction */
    private $action;

    /** @var MockInterface */
    private $manipulateService;

    public function setUp(): void
    {
        parent::setUp();

        $this->manipulateService = Mockery::mock(ManipulateService::class);
        $this->app->instance(ManipulateService::class, $this->manipulateService);

        $this->action = $this->app->make(PileAction::class);
    }

    public function test_can_not_manipulate_when_wrong_position()
    {
        $position = 'test';

        $this->expectException(UnknownPositionException::class);
        $this->expectExceptionMessage("The position $position doesn't exist");
        $this->action->manipulate($position, 1, 2, []);
    }

    public function test_can_not_move_when_blocks_on_the_same_row()
    {
        $position = 'onto';
        $movingBlock = 0;
        $receivingBlock = 1;
        $blocks = $this->getBlocks();

        $this->manipulateService
            ->shouldReceive('getBlockRowIndex')
            ->once()
            ->with($movingBlock, $blocks)
            ->andReturn(0);
        $this->manipulateService
            ->shouldReceive('getBlockRowIndex')
            ->once()
            ->with($receivingBlock, $blocks)
            ->andReturn(0);

        $this->expectException(ManipulateException::class);
        $this->expectExceptionMessage('The block are on the same row');
        $this->action->manipulate($position, $movingBlock, $receivingBlock, $blocks);
    }

    public function test_can_move_onto()
    {
        $position = 'onto';
        $movingBlock = 0;
        $receivingBlock = 2;
        $blocks = $this->getBlocks();
        $blockAfterStepOne = [0 => [0, 1], 1 => [], 2 => [2], 3 => [3], 4 => [4]];
        $blockAfterStepTwo = [0 => [1], 1 => [], 2 => [2], 3 => [3], 4 => [4]];
        $blockAfterStepThree = [0 => [], 1 => [], 2 => [2], 3 => [3], 4 => [4]];
        $finalBlock = [0 => [], 1 => [1], 2 => [2, 0, 1], 3 => [3], 4 => [4]];
        $movingBlockRowIndex = 0;
        $receivingBlockRowIndex = 2;
        $receivingBlockIndex = 0;
        $movingBlockRow = $blocks[$movingBlockRowIndex];
        $blockIds = [0, 1];

            $this->manipulateService
            ->shouldReceive('getBlockRowIndex')
            ->once()
            ->with($movingBlock, $blocks)
            ->andReturn($movingBlockRowIndex);
        $this->manipulateService
            ->shouldReceive('getBlockRowIndex')
            ->once()
            ->with($receivingBlock, $blocks)
            ->andReturn($receivingBlockRowIndex);

        $this->manipulateService
            ->shouldReceive('getTargetIndex')
            ->once()
            ->with($blocks[$receivingBlockRowIndex], $receivingBlock)
            ->andReturn($receivingBlockIndex);

        $this->manipulateService
            ->shouldReceive('moveBlockOverTargetToInitialRow')
            ->once()
            ->with($receivingBlockRowIndex, $receivingBlockIndex, $blocks)
            ->andReturn($blockAfterStepOne);

        $this->manipulateService
            ->shouldReceive('getBlocksToMove')
            ->once()
            ->with($movingBlockRow, $movingBlock)
            ->andReturn($blockIds);

        $this->manipulateService
            ->shouldReceive('getTargetIndex')
            ->once()
            ->with($movingBlockRow, $blockIds[0])
            ->andReturn(0);

        $this->manipulateService
            ->shouldReceive('getTargetIndex')
            ->once()
            ->with($movingBlockRow, $blockIds[1])
            ->andReturn(0);

        $this->manipulateService
            ->shouldReceive('removeBlock')
            ->once()
            ->with($blockAfterStepOne, $movingBlockRowIndex, 0)
            ->andReturn($blockAfterStepTwo);

        $this->manipulateService
            ->shouldReceive('removeBlock')
            ->once()
            ->with($blockAfterStepTwo, $movingBlockRowIndex, 0)
            ->andReturn($blockAfterStepThree);

        $this->manipulateService
            ->shouldReceive('addBlocksToRow')
            ->once()
            ->with($blockIds, $receivingBlockRowIndex, $blockAfterStepThree)
            ->andReturn($finalBlock);

        $this->assertEquals($finalBlock, $this->action->manipulate($position, $movingBlock, $receivingBlock, $blocks));
    }

    public function test_can_move_over()
    {
        $position = 'over';
        $movingBlock = 0;
        $receivingBlock = 2;
        $blocks = $this->getBlocks();
        $blockAfterStepOne = [0 => [1], 1 => [], 2 => [2, 3], 3 => [], 4 => [4]];
        $blockAfterStepTwo = [0 => [], 1 => [], 2 => [2, 3], 3 => [], 4 => [4]];
        $finalBlock = [0 => [], 1 => [1], 2 => [2, 3, 0, 1], 3 => [], 4 => [4]];
        $movingBlockRowIndex = 0;
        $receivingBlockRowIndex = 2;
        $movingBlockRow = $blocks[$movingBlockRowIndex];
        $blockIds = [0, 1];

        $this->manipulateService
            ->shouldReceive('getBlockRowIndex')
            ->once()
            ->with($movingBlock, $blocks)
            ->andReturn($movingBlockRowIndex);
        $this->manipulateService
            ->shouldReceive('getBlockRowIndex')
            ->once()
            ->with($receivingBlock, $blocks)
            ->andReturn($receivingBlockRowIndex);

        $this->manipulateService
            ->shouldReceive('getBlocksToMove')
            ->once()
            ->with($movingBlockRow, $movingBlock)
            ->andReturn($blockIds);

        $this->manipulateService
            ->shouldReceive('getTargetIndex')
            ->once()
            ->with($movingBlockRow, $blockIds[0])
            ->andReturn(0);

        $this->manipulateService
            ->shouldReceive('getTargetIndex')
            ->once()
            ->with($movingBlockRow, $blockIds[1])
            ->andReturn(0);

        $this->manipulateService
            ->shouldReceive('removeBlock')
            ->once()
            ->with($blocks, $movingBlockRowIndex, 0)
            ->andReturn($blockAfterStepOne);

        $this->manipulateService
            ->shouldReceive('removeBlock')
            ->once()
            ->with($blockAfterStepOne, $movingBlockRowIndex, 0)
            ->andReturn($blockAfterStepTwo);

        $this->manipulateService
            ->shouldReceive('addBlocksToRow')
            ->once()
            ->with($blockIds, $receivingBlockRowIndex, $blockAfterStepTwo)
            ->andReturn($finalBlock);

        $this->assertEquals($finalBlock, $this->action->manipulate($position, $movingBlock, $receivingBlock, $blocks));
    }

    private function getBlocks(): array
    {
        return [0 => [0, 1], 1 => [], 2 => [2, 3], 3 => [], 4 => [4]];
    }
}
