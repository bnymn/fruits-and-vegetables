<?php
declare(strict_types=1);

namespace App\Tests\Infrastructure\Repository\InMemory;

use App\Domain\Repository\VegetableRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class VegetableRepositoryTest extends KernelTestCase
{
    private VegetableRepositoryInterface $repository;

    protected function setUp(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        /** @var VegetableRepositoryInterface $repository */
        $repository = $container->get(VegetableRepositoryInterface::class);
        $this->repository = $repository;
    }

    #[Test]
    public function shouldSaveAndDeleteVegetable(): void
    {
        // Arrange
        $vegetable = $this
            ->repository
            ->createEmptyVegetable()
            ->setId(2)
            ->setName('Vegetable 2')
            ->setQuantity(10);

        // Act (1)
        $this->repository->save($vegetable);

        // Assert
        $allVegetables = $this->repository->findAll();
        $this->assertCount(1, $allVegetables);
        $this->assertSame('Vegetable 2', $allVegetables[0]->getName());
        $this->assertSame(10, $allVegetables[0]->getQuantity());

        // Act
        $this->repository->deleteById($vegetable->getId());

        // Assert
        $allVegetables = $this->repository->findAll();
        $this->assertCount(0, $allVegetables);
    }
}
