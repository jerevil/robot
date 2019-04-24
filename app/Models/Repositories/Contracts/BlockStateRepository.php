<?php declare(strict_types=1);

namespace App\Models\Repositories\Contracts;

use App\Models\BlockState;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface BlockStateRepository
{
    /**
     * @param  int  $nbBlock
     * @return BlockState
     */
    public function create(int $nbBlock): BlockState;

    /**
     * @param  BlockState  $blockState
     * @param  array  $blocks
     * @return BlockState
     */
    public function updateBlock(BlockState $blockState, array $blocks): BlockState;

    /**
     * @return BlockState
     * @throws ModelNotFoundException
     */
    public function findLastUnfinished(): BlockState;

    /**
     * @param  BlockState  $blockState
     * @return BlockState
     */
    public function finish(BlockState $blockState): BlockState;
}
