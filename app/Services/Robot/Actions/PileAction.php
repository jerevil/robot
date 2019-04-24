<?php declare(strict_types=1);

namespace App\Services\Robot\Actions;

use App\Services\Robot\Actions\Contracts\ActionService;

class PileAction extends AbstractAction implements ActionService
{
    protected function onto(int $movingBlock, int $receivingBlock, array $blocks): array
    {
        $movingBlockRowIndex = $this->manipulateService->getBlockRowIndex($movingBlock, $blocks);
        $receivingBlockRowIndex = $this->manipulateService->getBlockRowIndex($receivingBlock, $blocks);

        $this->validateBlocksNotOnTheSameRow($movingBlockRowIndex, $receivingBlockRowIndex);

        $receivingBlockIndex = $this->manipulateService->getTargetIndex(
            $blocks[$receivingBlockRowIndex],
            $receivingBlock
        );

        $blocks = $this->manipulateService->moveBlockOverTargetToInitialRow(
            $receivingBlockRowIndex,
            $receivingBlockIndex,
            $blocks
        );

        $movingBlockRow = $blocks[$movingBlockRowIndex];
        $blockIds = $this->manipulateService->getBlocksToMove($movingBlockRow, $movingBlock);

        foreach ($blockIds as $blockId) {
            $blockIndex = $this->manipulateService->getTargetIndex($movingBlockRow, $blockId);
            $blocks = $this->manipulateService->removeBlock($blocks, $movingBlockRowIndex, $blockIndex);
        }

        return $this->manipulateService->addBlocksToRow($blockIds, $receivingBlockRowIndex, $blocks);
    }

    protected function over(int $movingBlock, int $receivingBlock, array $blocks): array
    {
        $movingBlockRowIndex = $this->manipulateService->getBlockRowIndex($movingBlock, $blocks);
        $receivingBlockRowIndex = $this->manipulateService->getBlockRowIndex($receivingBlock, $blocks);

        $this->validateBlocksNotOnTheSameRow($movingBlockRowIndex, $receivingBlockRowIndex);

        $movingBlockRow = $blocks[$movingBlockRowIndex];
        $blockIds = $this->manipulateService->getBlocksToMove($movingBlockRow, $movingBlock);

        foreach ($blockIds as $blockId) {
            $blockIndex = $this->manipulateService->getTargetIndex($movingBlockRow, $blockId);
            $blocks = $this->manipulateService->removeBlock($blocks, $movingBlockRowIndex, $blockIndex);
        }

        return $this->manipulateService->addBlocksToRow($blockIds, $receivingBlockRowIndex, $blocks);
    }
}
