<?php declare(strict_types=1);

namespace App\Services\Robot;

use App\Exceptions\ManipulateException;
use App\Services\Robot\Contracts\ManipulateService as ManipulateServiceContract;

class ManipulateService implements ManipulateServiceContract
{
    public function getBlockRowIndex(int $blockId, array $blocks): int
    {
        foreach ($blocks as $i => $block) {
            if (in_array($blockId, $block)) {
                return $i;
            }
        }

        throw new ManipulateException('Unknown block id:'.$blockId);
    }

    public function moveBlockOverTargetToInitialRow(int $rowIndex, int $blockIndex, array $blocks): array
    {
        $rowBlocks = $blocks[$rowIndex];

        while ($blockIndex < count($rowBlocks) - 1) {
            $blockIndex++;

            $blockId = $blocks[$rowIndex][$blockIndex];
            $blocks[$blockId][] = $blockId;

            unset($blocks[$rowIndex][$blockIndex]);
        }

        return $blocks;
    }

    public function getTargetIndex(array $blockRow, int $blockId): int
    {
        return array_search($blockId, $blockRow);
    }

    public function removeBlock(array $blocks, int $rowIndex, int $blockIndex): array
    {
        unset($blocks[$rowIndex][$blockIndex]);

        return $blocks;
    }

    public function addBlocksToRow(array $movingBlocks, int $receivingBlockRowIndex, array $blocks): array
    {
        foreach ($movingBlocks as $blockId) {
            array_push($blocks[$receivingBlockRowIndex], $blockId);
        }

        $blocks[$receivingBlockRowIndex] = array_values($blocks[$receivingBlockRowIndex]);

        return $blocks;
    }

    public function getBlocksToMove(array $blockRow, int $movingBlock): array
    {
        $blockIndex = $this->getTargetIndex($blockRow, $movingBlock);
        $blockIds = [];

        for ($i = $blockIndex; $i < count($blockRow); $i++) {
            $blockIds[] = $blockRow[$i];
        }

        return $blockIds;
    }
}
