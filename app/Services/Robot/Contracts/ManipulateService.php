<?php declare(strict_types=1);

namespace App\Services\Robot\Contracts;

use App\Exceptions\ManipulateException;

interface ManipulateService
{
    /**
     * @param  int  $blockId
     * @param  array  $blocks
     * @return int
     * @throws ManipulateException
     */
    public function getBlockRowIndex(int $blockId, array $blocks): int;

    /**
     * @param  int  $rowIndex
     * @param  int  $blockIndex
     * @param  array  $blocks
     * @return array
     */
    public function moveBlockOverTargetToInitialRow(int $rowIndex, int $blockIndex, array $blocks): array;

    /**
     * @param  array  $blockRow
     * @param  int  $blockId
     * @return int
     */
    public function getTargetIndex(array $blockRow, int $blockId): int;

    /**
     * @param  array  $blocks
     * @param  int  $rowIndex
     * @param  int  $blockIndex
     * @return array
     */
    public function removeBlock(array $blocks, int $rowIndex, int $blockIndex): array;

    /**
     * @param  array  $movingBlocks
     * @param  int  $receivingBlockRowIndex
     * @param  array  $blocks
     * @return array
     */
    public function addBlocksToRow(array $movingBlocks, int $receivingBlockRowIndex, array $blocks): array;

    /**
     * @param  array  $blockRow
     * @param  int  $movingBlock
     * @return array
     */
    public function getBlocksToMove(array $blockRow, int $movingBlock): array;
}
