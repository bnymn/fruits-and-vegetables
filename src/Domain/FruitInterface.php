<?php
declare(strict_types=1);

namespace App\Domain;

interface FruitInterface
{
    public function getId(): int;

    public function setId(int $id): static;

    public function getName(): string;

    public function setName(string $name): static;

    public function getQuantity(): int;

    public function setQuantity(int $quantity): static;

    /**
     * @return array{
     *      id: int,
     *      name: string,
     *      quantity: int,
     *  }
     */
    public function toArray(): array;
}
