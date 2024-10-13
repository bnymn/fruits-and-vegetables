<?php
declare(strict_types=1);

namespace App\Application\DataImport\Fruit;

use App\Application\DataImport\CombinedDataImporter;
use App\Application\Exception\InvalidDataException;

class FruitValidator
{
    /**
     * @param array<string,string> $fruitDataRow
     *
     * @throws InvalidDataException
     */
    public function validate(array $fruitDataRow): void
    {
        $this->validateId($fruitDataRow[CombinedDataImporter::COL_ID] ?? '');
        $this->validateName($fruitDataRow[CombinedDataImporter::COL_NAME] ?? '');
        $this->validateQuantity($fruitDataRow[CombinedDataImporter::COL_QUANTITY] ?? '');
    }

    /**
     * @throws InvalidDataException
     */
    public function validateId(string|int|null $id): void
    {
        if (!is_numeric($id)) {
            throw new InvalidDataException(sprintf(
                'Attribute "%s" does not have numeric value. Invalid value: "%s".',
                CombinedDataImporter::COL_ID, $id
            ));
        }
    }

    /**
     * @throws InvalidDataException
     */
    public function validateName(string $name): void
    {
        if (empty($name)) {
            throw new InvalidDataException(sprintf(
                'Attribute "%s" is required.',
                CombinedDataImporter::COL_NAME
            ));
        }
    }

    /**
     * @throws InvalidDataException
     */
    public function validateQuantity(string|int $quantity): void
    {
        if (!is_numeric($quantity)) {
            throw new InvalidDataException(sprintf(
                'Attribute "%s" does not have numeric value. Invalid value: "%s".',
                CombinedDataImporter::COL_QUANTITY, $quantity
            ));
        }
    }
}
