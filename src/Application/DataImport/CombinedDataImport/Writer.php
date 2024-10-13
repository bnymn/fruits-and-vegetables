<?php
declare(strict_types=1);

namespace App\Application\DataImport\CombinedDataImport;

use App\Application\DataImport\CombinedDataImporter;
use App\Application\DataImport\Fruit\Writer as FruitWriter;
use App\Application\DataImport\Vegetable\Writer as VegetableWriter;
use App\Application\DataImport\WriterInterface;

class Writer implements WriterInterface
{
    public function __construct(
        private FruitWriter $fruitWriter,
        private VegetableWriter $vegetableWriter,
    ) {
    }

    /**
     * @param array<string,string> $dataRow
     */
    public function write(array $dataRow): void
    {
        $type = $dataRow[CombinedDataImporter::COL_TYPE];
        match ($type) {
            CombinedDataImporter::TYPE_FRUIT => $this->fruitWriter->write($dataRow),
            CombinedDataImporter::TYPE_VEGETABLE => $this->vegetableWriter->write($dataRow),
            default => throw new \RuntimeException(sprintf(
                'No writer found for the type "%s"',
                $type
            ))
        };
    }
}
