<?php
declare(strict_types=1);

namespace App\Application\DataImport;

interface TransformerInterface
{
    /**
     * @param array<string,string> $dataRow
     *
     * @return array<string,string>
     */
    public function transform(array $dataRow): array;
}
