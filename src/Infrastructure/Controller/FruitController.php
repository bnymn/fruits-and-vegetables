<?php
declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\DataImport\Fruit\FruitValidator;
use App\Application\Exception\InvalidDataException;
use App\Domain\Repository\FruitRepositoryInterface;
use App\Infrastructure\Entity\Fruit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

#[Route('/fruits', name: 'fruit_')]
class FruitController extends AbstractController
{
    public function __construct(
        private FruitRepositoryInterface $fruitRepository,
        private FruitValidator           $fruitValidator,
        private DenormalizerInterface    $denormalizer,
        private DecoderInterface         $decoder,
    ) {}

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json(
            $this->fruitRepository->findAll()
        );
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/', name: 'create', methods: ['POST'])]
    public function create(
        Request $request,
    ): JsonResponse
    {
        $requestBody = $request->getContent();
        $fruitDataRow = $this->decoder->decode($requestBody, 'json');
        try {
            if (!is_array($fruitDataRow)) {
                throw new InvalidDataException("Cannot convert json to array.");
            }
            $this->fruitValidator->validate($fruitDataRow);
        } catch (InvalidDataException $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST, [], [
                'json_encode_options' => JSON_UNESCAPED_UNICODE
            ]);
        }

        $fruit = $this->denormalizer->denormalize($fruitDataRow, Fruit::class);
        if (!($fruit instanceof Fruit)) {
            throw new InvalidDataException("Cannot convert json to Fruit object.");
        }
        $this->fruitRepository->save($fruit);

        return $this->json($fruitDataRow, Response::HTTP_CREATED);
    }
}
