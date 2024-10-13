<?php
declare(strict_types=1);

namespace App\Application\DataImport\Vegetable;

use App\Application\DataImport\WriterInterface;
use App\Domain\Repository\VegetableRepositoryInterface;
use App\Domain\VegetableInterface;

class Writer implements WriterInterface
{
    private const string COL_ID = 'id';
    private const string COL_NAME = 'name';
    private const string COL_QUANTITY = 'quantity';

    public function __construct(
        private VegetableRepositoryInterface $vegetableRepository
    ) { }

    /**
     * @param array<string,string> $dataRow
     */
    public function write(array $dataRow): void
    {
        $vegetable = $this->transformToEntity($dataRow);
        $this->vegetableRepository->save($vegetable);
    }

    /**
     * @param array<string,string> $dataRow
     */
    private function transformToEntity(array $dataRow): VegetableInterface
    {
        return $this
            ->vegetableRepository
            ->createEmptyVegetable()
            ->setId((int) $dataRow[self::COL_ID])
            ->setName($dataRow[self::COL_NAME])
            ->setQuantity((int) $dataRow[self::COL_QUANTITY])
            ;
    }
}
