<?php
declare(strict_types=1);

namespace App\Application\DataImport\CombinedDataImport;

use App\Application\DataImport\CombinedDataImporter;
use App\Application\DataImport\TransformerInterface;

class Transformer implements TransformerInterface
{
    public function transform(array $dataRow): array
    {
        $result = [
            CombinedDataImporter::COL_ID        => $dataRow[CombinedDataImporter::COL_ID],
            CombinedDataImporter::COL_NAME      => $dataRow[CombinedDataImporter::COL_NAME],
            CombinedDataImporter::COL_QUANTITY  => $dataRow[CombinedDataImporter::COL_QUANTITY],
            CombinedDataImporter::COL_TYPE      => $dataRow[CombinedDataImporter::COL_TYPE],
        ];

        if ($dataRow[CombinedDataImporter::COL_UNIT] === CombinedDataImporter::UNIT_KG) {
            $result[CombinedDataImporter::COL_QUANTITY] = (string) $this->convertKgToG((int) $result[CombinedDataImporter::COL_QUANTITY]);
        }

        return $result;
    }

    private function convertKgToG(int $kg): int
    {
        return $kg * 1000;
    }
}
