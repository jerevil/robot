<?php declare(strict_types=1);

namespace App\Console\Commands\Robot;

use App\Models\Repositories\Contracts\BlockStateRepository;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InitCommand extends Command
{
    /** @var string */
    protected $signature = 'robot:init {nbBlock}';

    /** @var string */
    protected $description = 'Robot command to initialize the program';

    public function handle(BlockStateRepository $blockStateRepository): void
    {
        $nbBlock = (int) $this->argument('nbBlock');

        if ($nbBlock === 0) {
            throw new Exception('The number of block should be > to 0');
        }

        try {
            $blockStateRepository->findLastUnfinished();

            $this->line('<info>You have already a robot initialised.</info>');
            $this->line('<info>Before to init a new robot you have to run the command: php artisan robot:quit.</info>');
        } catch (ModelNotFoundException $exception) {
            $blockStateRepository->create($nbBlock);
        }
    }
}
