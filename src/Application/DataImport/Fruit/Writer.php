<?php
declare(strict_types=1);

namespace App\Application\DataImport\Fruit;

use App\Application\DataImport\WriterInterface;
use App\Domain\FruitInterface;
use App\Domain\Repository\FruitRepositoryInterface;

class Writer implements WriterInterface
{
    private const string COL_ID = 'id';
    private const string COL_NAME = 'name';
    private const string COL_QUANTITY = 'quantity';

    public function __construct(
        private FruitRepositoryInterface $fruitRepository
    ) { }

    /**
     * @param array<string,string> $dataRow
     */
    public function write(array $dataRow): void
    {
        $fruit = $this->transformToEntity($dataRow);
        $this->fruitRepository->save($fruit);
    }

    /**
     * @param array<string,string> $dataRow
     */
    private function transformToEntity(array $dataRow): FruitInterface
    {
        return $this
            ->fruitRepository
            ->createEmptyFruit()
            ->setId((int) $dataRow[self::COL_ID])
            ->setName($dataRow[self::COL_NAME])
            ->setQuantity((int) $dataRow[self::COL_QUANTITY])
            ;
    }
}
