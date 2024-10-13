<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\FruitInterface;

interface FruitRepositoryInterface
{
    public function save(FruitInterface $fruit): FruitInterface;

    public function deleteById(int $id): void;

    /**
     * @return array<FruitInterface>
     */
    public function findAll(): array;

    public function deleteAll(): void;

    public function createEmptyFruit(): FruitInterface;
}
