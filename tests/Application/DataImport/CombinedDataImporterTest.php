<?php
declare(strict_types=1);

namespace App\Tests\Application\DataImport;

use App\Application\DataImport\CombinedDataImporter;
use App\Application\Exception\InvalidDataException;
use App\Domain\DataImporterInterface;
use App\Domain\Repository\FruitRepositoryInterface;
use App\Domain\Repository\VegetableRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CombinedDataImporterTest extends KernelTestCase
{
    private DataImporterInterface $dataImporter;
    private FruitRepositoryInterface $fruitRepository;
    private VegetableRepositoryInterface $vegetableRepository;

    protected function setUp(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        /** @var CombinedDataImporter $dataImporter */
        $dataImporter = $container->get(CombinedDataImporter::class);
        $this->dataImporter = $dataImporter;

        /** @var VegetableRepositoryInterface $vegetableRepository */
        $vegetableRepository = $container->get(VegetableRepositoryInterface::class);
        $this->vegetableRepository = $vegetableRepository;

        /** @var FruitRepositoryInterface $fruitRepository */
        $fruitRepository = $container->get(FruitRepositoryInterface::class);
        $this->fruitRepository = $fruitRepository;
    }

    #[Test]
    public function shouldImportCombinedData(): void
    {
        // Act
        $this->dataImporter->import('var/imports/request.json');

        // Assert
        $vegetables = $this->vegetableRepository->findAll();
        $this->assertCount(10, $vegetables);
        $this->vegetableRepository->deleteAll();

        $fruits = $this->fruitRepository->findAll();
        $this->assertCount(10, $fruits);
        $this->fruitRepository->deleteAll();
    }

    #[Test]
    public function shouldNotImportNonExistingFile(): void
    {
        // Assert
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionMessage('File content is invalid');

        // Act
        $this->dataImporter->import('var/imports/non_existing_file.json');
    }
}
