<?php declare(strict_types=1);

namespace Tests\Feature\Models\Repositories;

use App\Models\BlockState;
use App\Models\Repositories\Contracts\BlockStateRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class BlockStateRepositoryTest extends TestCase
{
    /** @var BlockStateRepository */
    protected $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->app->make(BlockStateRepository::class);
    }

    public function test_can_create()
    {
        $nbBlock = 5;
        $blockState = $this->repository->create($nbBlock);

        $this->assertCount($nbBlock, $blockState->blocks);
    }

    public function test_can_update_block()
    {
        $nbBlock = 5;
        $blockState = $this->repository->create($nbBlock);
        $blocks = $blockState->blocks;

        $this->assertCount($nbBlock, $blocks);

        array_pop($blocks);
        $updatedBlockState = $this->repository->updateBlock($blockState, $blocks);

        $this->assertCount(4, $updatedBlockState->blocks);
    }

    public function test_can_find_last_unfinished()
    {
        factory(BlockState::class, 2)->create(['finished' => true]);
        $blockState = factory(BlockState::class)->create(['finished' => false]);

        $found = $this->repository->findLastUnfinished();

        $this->assertEquals($blockState->id, $found->id);
    }

    public function test_can_not_find_last_unfinished()
    {
        factory(BlockState::class, 2)->create(['finished' => true]);

        $this->expectException(ModelNotFoundException::class);
        $this->repository->findLastUnfinished();
    }

    public function test_can_finish()
    {
        $blockState = factory(BlockState::class)->create(['finished' => false]);

        $this->assertFalse($blockState->finished);

        $blockState = $this->repository->finish($blockState);

        $this->assertTrue($blockState->finished);
    }
}
