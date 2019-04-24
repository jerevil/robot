<?php declare(strict_types=1);

namespace App\Console\Commands\Robot;

use App\Models\Repositories\Contracts\BlockStateRepository;
use App\Services\Robot\Contracts\RobotService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ManipulateCommand extends Command
{
    /** @var string */
    protected $signature = 'robot:manipulate {action} {movingBlock} {position} {receivingBlock}';

    /** @var string */
    protected $description = 'Robot command to manipulate block';


    public function handle(BlockStateRepository $blockStateRepository, RobotService $robotService): void
    {
        try {
            $blockState = $blockStateRepository->findLastUnfinished();
            $blocks = $robotService->manipulate(
                (string) $this->argument('action'),
                (string) $this->argument('position'),
                (int) $this->argument('movingBlock'),
                (int) $this->argument('receivingBlock'),
                $blockState->blocks
            );

            $blockStateRepository->updateBlock($blockState, $blocks);
        } catch (ModelNotFoundException $exception) {
            $this->line('<info>You need to init the robot.</info>');
            $this->line('<info>Run the command: php artisan robot:init.</info>');
        } catch (Exception $exception) {
        }
    }
}
