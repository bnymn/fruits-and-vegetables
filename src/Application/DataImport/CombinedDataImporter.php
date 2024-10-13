<?php
declare(strict_types=1);

namespace App\Application\DataImport;

class CombinedDataImporter extends AbstractDataImporter
{
    public const string COL_ID = 'id';
    public const string COL_NAME = 'name';
    public const string COL_TYPE = 'type';
    public const string COL_QUANTITY = 'quantity';
    public const string COL_UNIT = 'unit';
    public const string UNIT_KG = 'kg';
    public const string TYPE_VEGETABLE = 'vegetable';
    public const string TYPE_FRUIT = 'fruit';
}
