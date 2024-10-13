<?php
declare(strict_types=1);

namespace App\Infrastructure\Repository\InMemory;

use App\Domain\Repository\VegetableRepositoryInterface;
use App\Domain\VegetableInterface;
use App\Infrastructure\Entity\Vegetable;

class VegetableRepository implements VegetableRepositoryInterface
{
    /**
     * @var array<int, VegetableInterface>
     */
    private static array $vegetables = [];

    public function save(VegetableInterface $vegetable): VegetableInterface
    {
        self::$vegetables[] = $vegetable;

        return $vegetable;
    }

    public function deleteById(int $id): void
    {
        foreach (self::$vegetables as $key => $vegetable) {
            if ($vegetable->getId() === $id) {
                unset(self::$vegetables[$key]);
            }
        }
    }

    public function findAll(): array
    {
        return self::$vegetables;
    }

    public function createEmptyVegetable(): VegetableInterface
    {
        return new Vegetable();
    }

    public function deleteAll(): void
    {
        self::$vegetables = [];
    }
}
