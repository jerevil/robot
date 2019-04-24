<?php declare(strict_types=1);

namespace Test\Unit\Console\Commands\Robot;

use App\Models\BlockState;
use App\Models\Repositories\Contracts\BlockStateRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class InitCommandTest extends TestCase
{
    /** @var MockInterface */
    private $blockStateRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->blockStateRepository = Mockery::mock(BlockStateRepository::class);
        $this->app->instance(BlockStateRepository::class, $this->blockStateRepository);
    }

    public function test_can_init()
    {
        $nbBlock = 10;

        $this->blockStateRepository
            ->shouldReceive('findLastUnfinished')
            ->once()
            ->andThrow(ModelNotFoundException::class);

        $this->blockStateRepository
            ->shouldReceive('create')
            ->once()
            ->with($nbBlock);

        $this->artisan('robot:init ' . $nbBlock);
    }

    public function test_can_not_init_when_already_init()
    {
        $nbBlock = 10;
        $blockState = factory(BlockState::class)->make();

        $this->blockStateRepository
            ->shouldReceive('findLastUnfinished')
            ->once()
            ->andReturn($blockState);

        $this->blockStateRepository->shouldNotReceive('create');

        $this->artisan('robot:init ' . $nbBlock);
    }

    public function test_can_not_init_when_nb_block_equal_0()
    {
        $nbBlock = 'test';

        $this->blockStateRepository->shouldNotHaveBeenCalled();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The number of block should be > to 0');
        $this->artisan('robot:init ' . $nbBlock);
    }
}
