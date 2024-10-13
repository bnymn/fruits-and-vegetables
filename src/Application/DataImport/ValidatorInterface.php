<?php
declare(strict_types=1);

namespace App\Application\DataImport;

interface ValidatorInterface
{
    /**
     * @param array<string,string> $dataRow
     */
    public function validate(array $dataRow): void;
}
