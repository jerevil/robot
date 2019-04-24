<?php declare(strict_types=1);

namespace App\Services\Robot\Actions\Contracts;

use App\Exceptions\ManipulateException;
use App\Exceptions\UnknownPositionException;

interface ActionService
{
    /**
     * @param  string  $position
     * @param  int  $movingBlock
     * @param  int  $receivingBlock
     * @param  array  $blocks
     * @return array
     * @throws ManipulateException
     * @throws UnknownPositionException
     */
    public function manipulate(string $position, int $movingBlock, int $receivingBlock, array $blocks): array;
}
