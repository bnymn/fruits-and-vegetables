<?php
declare(strict_types=1);

namespace App\Tests\Infrastructure\Repository\InMemory;

use App\Domain\Repository\FruitRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FruitRepositoryTest extends KernelTestCase
{
    private FruitRepositoryInterface $repository;

    protected function setUp(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        /** @var FruitRepositoryInterface $repository */
        $repository = $container->get(FruitRepositoryInterface::class);
        $this->repository = $repository;
    }

    #[Test]
    public function shouldSaveAndDeleteVegetable(): void
    {
        // Arrange
        $vegetable = $this
            ->repository
            ->createEmptyFruit()
            ->setId(2)
            ->setName('Fruit 2')
            ->setQuantity(10);

        // Act (1)
        $this->repository->save($vegetable);

        // Assert
        $allVegetables = $this->repository->findAll();
        $this->assertCount(1, $allVegetables);
        $this->assertSame('Fruit 2', $allVegetables[0]->getName());
        $this->assertSame(10, $allVegetables[0]->getQuantity());

        // Act
        $this->repository->deleteById($vegetable->getId());

        // Assert
        $allVegetables = $this->repository->findAll();
        $this->assertCount(0, $allVegetables);
    }
}
