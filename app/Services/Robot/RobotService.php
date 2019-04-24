<?php declare(strict_types=1);

namespace App\Services\Robot;

use App\Exceptions\ManipulateException;
use App\Services\Robot\Actions\Contracts\ActionService;
use App\Services\Robot\Actions\Illuminate\ActionManager;
use App\Services\Robot\Contracts\RobotService as ManipulateServiceContract;

class RobotService implements ManipulateServiceContract
{
    /** @var ActionManager */
    protected $actionManager;

    public function __construct(ActionManager $actionManager)
    {
        $this->actionManager = $actionManager;
    }

    public function manipulate(
        string $action,
        string $position,
        int $movingBlock,
        int $receivingBlock,
        array $blocks
    ): array {
        $this->validate($movingBlock, $receivingBlock, $blocks);

        /** @var ActionService $actionService */
        $actionService = $this->actionManager->driver($action);

        return $actionService->manipulate($position, $movingBlock, $receivingBlock, $blocks);
    }

    protected function validate(int $movingBlock, int $receivingBlock, array $blocks): void
    {
        if ($movingBlock === $receivingBlock) {
            throw new ManipulateException('The moving block and the receiving block should not be the same');
        }

        if (($movingBlock > $receivingBlock ? $movingBlock : $receivingBlock) > count($blocks) - 1) {
            throw new ManipulateException('You selected an inexistent block');
        }
    }
}
