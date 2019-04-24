<?php declare(strict_types=1);

namespace Tests\Unit\Services\Robot;

use App\Exceptions\ManipulateException;
use App\Services\Robot\Contracts\ManipulateService;
use Tests\TestCase;

class ManipulateServiceTest extends TestCase
{
    /** @var ManipulateService */
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(ManipulateService::class);
    }

    public function test_can_get_block_row_index()
    {
        $blocks = $this->getBlocks();

        $this->assertEquals(2, $this->service->getBlockRowIndex(3, $blocks));
    }

    public function test_cannot_get_block_row_index()
    {
        $blocks = $this->getBlocks();
        $blockId = 10;

        $this->expectException(ManipulateException::class);
        $this->expectExceptionMessage('Unknown block id:' . $blockId);
        $this->service->getBlockRowIndex($blockId, $blocks);
    }

    public function test_can_move_block_over_target_to_initial_row()
    {
        $blocks = $this->getBlocks();
        $expected = [0 => [0, 1], 1 => [], 2 => [2], 3 => [3], 4 => [4]];

        $this->assertEquals($expected, $this->service->moveBlockOverTargetToInitialRow(2, 0, $blocks));
    }

    public function test_can_get_target_index()
    {
        $blocks = $this->getBlocks();

        $this->assertEquals(1, $this->service->getTargetIndex($blocks[2], 3));
    }

    public function test_can_remove_block()
    {
        $blocks = $this->getBlocks();
        $expected = [0 => [0], 1 => [], 2 => [2, 3, 4], 3 => [], 4 => []];

        $this->assertEquals($expected, $this->service->removeBlock($blocks,0,  1));
    }

    public function test_can_add_blocks_to_row()
    {
        $blocks = $this->getBlocks();
        $expected = [0 => [0, 1, 5, 6], 1 => [], 2 => [2, 3, 4], 3 => [], 4 => []];

        $this->assertEquals($expected, $this->service->addBlocksToRow([5, 6], 0, $blocks));
    }

    public function test_can_get_blocks_to_move()
    {
        $blocks = $this->getBlocks();
        $expected = [3, 4];

        $this->assertEquals($expected, $this->service->getBlocksToMove($blocks[2], 3));
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
