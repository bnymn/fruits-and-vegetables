<?php
declare(strict_types=1);

namespace App\Infrastructure\Entity;

use App\Domain\VegetableInterface;

class Vegetable implements VegetableInterface
{
    private int $id;

    private string $name;

    private int $quantity;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return array{
     *     id: int,
     *     name: string,
     *     quantity: int,
     * }
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'quantity' => $this->quantity,
        ];
    }
}
