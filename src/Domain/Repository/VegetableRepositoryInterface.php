<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\VegetableInterface;

interface VegetableRepositoryInterface
{
    public function save(VegetableInterface $vegetable): VegetableInterface;

    public function deleteById(int $id): void;

    /**
     * @return array<VegetableInterface>
     */
    public function findAll(): array;

    public function deleteAll(): void;

    public function createEmptyVegetable(): VegetableInterface;
}
