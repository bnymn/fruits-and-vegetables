<?php
declare(strict_types=1);

namespace App\Application\DataImport\Vegetable;

use App\Application\DataImport\CombinedDataImporter;
use App\Application\Exception\InvalidDataException;

class VegetableValidator
{
    /**
     * @param array<string,string> $vegetableDataRow
     *
     * @throws InvalidDataException
     */
    public function validate(array $vegetableDataRow): void
    {
        $this->validateId($vegetableDataRow[CombinedDataImporter::COL_ID] ?? '');
        $this->validateName($vegetableDataRow[CombinedDataImporter::COL_NAME] ?? '');
        $this->validateQuantity($vegetableDataRow[CombinedDataImporter::COL_QUANTITY] ?? '');
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
