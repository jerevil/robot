<?php declare(strict_types=1);

namespace App\Console\Commands\Robot;

use App\Models\Repositories\Contracts\BlockStateRepository;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class QuitCommand extends Command
{
    /** @var string */
    protected $signature = 'robot:quit';

    /** @var string */
    protected $description = 'Robot command to quit the program';

    public function handle(BlockStateRepository $blockStateRepository): void
    {
        try {
            $blockState = $blockStateRepository->findLastUnfinished();

            $blockStateRepository->finish($blockState);
            $this->displayBlocks($blockState->blocks);
        } catch (ModelNotFoundException $exception) {

        }
    }

    protected function displayBlocks(array $blocks): void
    {
        foreach ($blocks as $i => $block) {
            $this->line('<info>'.$i.': '.implode(' ', $block).'</info>');
        }
    }
}
