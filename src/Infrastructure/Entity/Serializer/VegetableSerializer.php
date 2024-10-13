<?php
declare(strict_types=1);

namespace App\Infrastructure\Entity\Serializer;

use App\Infrastructure\Entity\Vegetable;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class VegetableSerializer implements NormalizerInterface, DenormalizerInterface
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
        /** @var Vegetable $object */
        return $object->toArray();
    }

    /**
     * @param array<mixed> $context
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Vegetable;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Vegetable::class => true,
        ];
    }

    /**
     * @param array<string,string|int|null> $data
     * @param array<mixed> $context
     *
     * @return Vegetable
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        return (new Vegetable())
            ->setId((int) $data['id'])
            ->setName((string) $data['name'])
            ->setQuantity((int) $data['quantity']);
    }

    /**
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     * @param array<mixed> $context
     * @return bool
     */
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === Vegetable::class;
    }
}
