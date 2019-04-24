<?php declare(strict_types=1);

namespace Test\Unit\Console\Commands\Robot;

use App\Models\BlockState;
use App\Models\Repositories\Contracts\BlockStateRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class QuitCommandTest extends TestCase
{
    /** @var MockInterface */
    private $blockStateRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->blockStateRepository = Mockery::mock(BlockStateRepository::class);
        $this->app->instance(BlockStateRepository::class, $this->blockStateRepository);
    }

    public function test_can_not_quit_when_not_init()
    {
        $this->blockStateRepository
            ->shouldReceive('findLastUnfinished')
            ->once()
            ->andThrow(ModelNotFoundException::class);

        $this->blockStateRepository->shouldNotReceive('finish');

        $this->artisan('robot:quit');
    }

    public function test_can_quit()
    {
        $blockState = factory(BlockState::class)->make();

        $this->blockStateRepository
            ->shouldReceive('findLastUnfinished')
            ->once()
            ->andReturn($blockState);
        $this->blockStateRepository
            ->shouldReceive('finish')
            ->once()
            ->with($blockState);

        $this->artisan('robot:quit');
    }

}
