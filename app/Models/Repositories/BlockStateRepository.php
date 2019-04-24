<?php declare(strict_types=1);

namespace App\Models\Repositories;

use App\Models\BlockState;
use App\Models\Repositories\Contracts\BlockStateRepository as BlockStateRepositoryContract;

class BlockStateRepository implements BlockStateRepositoryContract
{
    public function create(int $nbBlock): BlockState
    {
        $blocks = [];

        for ($i = 0; $i < $nbBlock; $i++) {
            $blocks[$i] = [$i];
        }

        return BlockState::Create(['blocks' => $blocks]);
    }

    public function updateBlock(BlockState $blockState, array $blocks): BlockState
    {
        $blockState->blocks = $blocks;
        $blockState->save();

        return $blockState;
    }

    public function findLastUnfinished(): BlockState
    {
        return BlockState::query()
            ->where('finished', '=', false)
            ->orderByDesc('id')
            ->firstOrFail();
    }

    public function finish(BlockState $blockState): BlockState
    {
        $blockState->finished = true;
        $blockState->save();

        return $blockState;
    }

}
