<?php
declare(strict_types=1);

namespace App\Application\DataImport;

interface ReaderInterface
{
    /**
     * @param string $fileName
     *
     * @return array<array<string,string>>
     */
    public function read(string $fileName): array;
}
