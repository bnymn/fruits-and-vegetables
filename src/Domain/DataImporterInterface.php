<?php
declare(strict_types=1);

namespace App\Domain;

interface DataImporterInterface
{
    public function import(string $fileName): void;
}
