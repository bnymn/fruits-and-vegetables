<?php
declare(strict_types=1);

namespace App\Application\DataImport\CombinedDataImport;

use App\Application\DataImport\CombinedDataImporter;
use App\Application\DataImport\Fruit\FruitValidator;
use App\Application\DataImport\ValidatorInterface;
use App\Application\DataImport\Vegetable\VegetableValidator;
use App\Application\Exception\InvalidDataException;

class Validator implements ValidatorInterface
{
    public function __construct(
        private FruitValidator $fruitValidator,
        private VegetableValidator $vegetableValidator,
    ) {
    }

    /**
     * @throws InvalidDataException
     */
    public function validate(array $dataRow): void
    {
        $type = $dataRow[CombinedDataImporter::COL_TYPE];
        $availableTypes = [CombinedDataImporter::TYPE_VEGETABLE, CombinedDataImporter::TYPE_FRUIT];

        match ($type) {
            CombinedDataImporter::TYPE_VEGETABLE => $this->vegetableValidator->validate($dataRow),
            CombinedDataImporter::TYPE_FRUIT => $this->fruitValidator->validate($dataRow),
            default => null,
        };

        if (!in_array($type, $availableTypes)) {
            throw new InvalidDataException(sprintf(
                'Attribute "%s" can only have the following values: %s. Invalid value: "%s".',
                CombinedDataImporter::COL_TYPE, join(',', $availableTypes), $type
            ));
        }
    }
}
