<?php
declare(strict_types=1);

namespace App\Infrastructure\Repository\InMemory;

use App\Domain\FruitInterface;
use App\Domain\Repository\FruitRepositoryInterface;
use App\Infrastructure\Entity\Fruit;

class FruitRepository implements FruitRepositoryInterface
{
    /**
     * @var array<int, FruitInterface>
     */
    private static array $fruits = [];

    public function save(FruitInterface $fruit): FruitInterface
    {
        self::$fruits[] = $fruit;

        return $fruit;
    }

    public function deleteById(int $id): void
    {
        foreach (self::$fruits as $key => $fruit) {
            if ($fruit->getId() === $id) {
                unset(self::$fruits[$key]);
            }
        }
    }

    public function findAll(): array
    {
        return self::$fruits;
    }

    public function createEmptyFruit(): FruitInterface
    {
        return new Fruit();
    }

    public function deleteAll(): void
    {
        self::$fruits = [];
    }
}
