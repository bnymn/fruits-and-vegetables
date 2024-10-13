<?php
declare(strict_types=1);

namespace App\Infrastructure\DataImport;

use App\Application\DataImport\ReaderInterface;
use App\Application\Exception\InvalidDataException;
use JsonException;

class Reader implements ReaderInterface
{
    /**
     * @return array<array<string,string>>
     *
     * @throws JsonException
     */
    public function read(string $fileName): array
    {
        $fileContent = file_exists($fileName) ? file_get_contents($fileName) : false;
        if ($fileContent === false) {
            throw new InvalidDataException('File content is invalid');
        }
        /** @var array<array<string,string>> $data */
        $data = json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR|JSON_UNESCAPED_UNICODE);

        return $data;
    }
}
