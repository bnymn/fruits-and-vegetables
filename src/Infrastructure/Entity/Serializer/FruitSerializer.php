<?php
declare(strict_types=1);

namespace App\Infrastructure\Entity\Serializer;

use App\Infrastructure\Entity\Fruit;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FruitSerializer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * @param array<mixed> $context
     *
     * @return array{
     *      id: int,
     *      name: string,
     *      quantity: int,
     *  }
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        /** @var Fruit $object */
        return $object->toArray();
    }

    /**
     * @param array<mixed> $context
     */
    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Fruit;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Fruit::class => true,
        ];
    }

    /**
     * @param array<string,string|int|null> $data
     * @param array<mixed> $context
     *
     * @return Fruit
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        return (new Fruit())
            ->setId((int) $data['id'])
            ->setName((string) $data['name'])
            ->setQuantity((int) $data['quantity'])
            ;
    }

    /**
     * @param array<mixed> $context
     */
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === Fruit::class;
    }
}
