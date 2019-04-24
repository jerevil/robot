<?php declare(strict_types=1);

namespace App\Services\Robot\Actions;

use App\Exceptions\ManipulateException;
use App\Exceptions\UnknownPositionException;
use App\Services\Robot\Contracts\ManipulateService;

abstract class AbstractAction
{
    /** @var ManipulateService */
    protected $manipulateService;

    public function __construct(ManipulateService $manipulateService)
    {
        $this->manipulateService = $manipulateService;
    }

    public function manipulate(string $position, int $movingBlock, int $receivingBlock, array $blocks): array
    {
        switch ($position) {
            case 'onto':
                return $this->onto($movingBlock, $receivingBlock, $blocks);
                break;
            case 'over':
                return $this->over($movingBlock, $receivingBlock, $blocks);
                break;
            default:
                throw new UnknownPositionException("The position $position doesn't exist");
        }
    }

    protected function validateBlocksNotOnTheSameRow(int $movingBlockIndex, int $receivingBlockIndex)
    {
        if ($movingBlockIndex === $receivingBlockIndex) {
            throw new ManipulateException('The block are on the same row');
        }
    }

    /**
     * @param  int  $movingBlock
     * @param  int  $receivingBlock
     * @param  array  $blocks
     * @return array
     * @throws ManipulateException
     */
    abstract protected function onto(int $movingBlock, int $receivingBlock, array $blocks): array;

    /**
     * @param  int  $movingBlock
     * @param  int  $receivingBlock
     * @param  array  $blocks
     * @return array
     * @throws ManipulateException
     */
    abstract protected function over(int $movingBlock, int $receivingBlock, array $blocks): array;
}
