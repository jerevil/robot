<?php declare(strict_types=1);

namespace App\Services\Robot\Actions;

use App\Services\Robot\Actions\Contracts\ActionService;

class MoveAction extends AbstractAction implements ActionService
{
    protected function onto(int $movingBlock, int $receivingBlock, array $blocks): array
    {
        $movingBlockRowIndex = $this->manipulateService->getBlockRowIndex($movingBlock, $blocks);
        $receivingBlockRowIndex = $this->manipulateService->getBlockRowIndex($receivingBlock, $blocks);

        $this->validateBlocksNotOnTheSameRow($movingBlockRowIndex, $receivingBlockRowIndex);

        $movingBlocIndex = $this->manipulateService->getTargetIndex($blocks[$movingBlockRowIndex], $movingBlock);
        $receivingBlockIndex = $this->manipulateService->getTargetIndex(
            $blocks[$receivingBlockRowIndex],
            $receivingBlock
        );

        $blocks = $this->manipulateService->moveBlockOverTargetToInitialRow(
            $movingBlockRowIndex,
            $movingBlocIndex,
            $blocks
        );
        $blocks = $this->manipulateService->moveBlockOverTargetToInitialRow(
            $receivingBlockRowIndex,
            $receivingBlockIndex,
            $blocks
        );

        $blocks = $this->manipulateService->removeBlock($blocks, $movingBlockRowIndex, $movingBlocIndex);

        return $this->manipulateService->addBlocksToRow([$movingBlock], $receivingBlockRowIndex, $blocks);
    }

    protected function over(int $movingBlock, int $receivingBlock, array $blocks): array
    {
        $movingBlockRowIndex = $this->manipulateService->getBlockRowIndex($movingBlock, $blocks);
        $receivingBlockRowIndex = $this->manipulateService->getBlockRowIndex($receivingBlock, $blocks);

        $this->validateBlocksNotOnTheSameRow($movingBlockRowIndex, $receivingBlockRowIndex);

        $movingBlocIndex = $this->manipulateService->getTargetIndex($blocks[$movingBlockRowIndex], $movingBlock);

        $blocks = $this->manipulateService->moveBlockOverTargetToInitialRow(
            $movingBlockRowIndex,
            $movingBlocIndex,
            $blocks
        );

        $blocks = $this->manipulateService->removeBlock($blocks, $movingBlockRowIndex, $movingBlocIndex);

        return $this->manipulateService->addBlocksToRow([$movingBlock], $receivingBlockRowIndex, $blocks);
    }
}
