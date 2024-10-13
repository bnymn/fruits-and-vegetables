<?php
declare(strict_types=1);

namespace App\Application\DataImport;

interface WriterInterface
{
    /**
     * @param array<string,string> $dataRow
     */
    public function write(array $dataRow): void;
}
