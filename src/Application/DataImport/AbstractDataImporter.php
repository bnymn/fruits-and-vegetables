<?php
declare(strict_types=1);

namespace App\Application\DataImport;

use App\Domain\DataImporterInterface;

abstract class AbstractDataImporter implements DataImporterInterface
{
    public function __construct(
        protected ReaderInterface $reader,
        protected ValidatorInterface $validator,
        protected TransformerInterface $transformer,
        protected WriterInterface $writer,
    ) {}

    public function import(string $fileName): void
    {
        $data = $this->reader->read($fileName);
        foreach ($data as $dataRow) {
            $this->validator->validate($dataRow);
            $dataRow = $this->transformer->transform($dataRow);
            $this->writer->write($dataRow);
        }
    }
}
