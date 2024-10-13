<?php
declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\DataImport\Vegetable\VegetableValidator;
use App\Application\Exception\InvalidDataException;
use App\Domain\Repository\VegetableRepositoryInterface;
use App\Infrastructure\Entity\Vegetable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

#[Route('/vegetables', name: 'vegetable_')]
class VegetableController extends AbstractController
{
    public function __construct(
        private VegetableRepositoryInterface $vegetableRepository,
        private VegetableValidator           $vegetableValidator,
        private DenormalizerInterface        $denormalizer,
        private DecoderInterface             $decoder,
    ) {}

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json(
            $this->vegetableRepository->findAll()
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
        $vegetableDataRow = $this->decoder->decode($requestBody, 'json');
        try {
            if (!is_array($vegetableDataRow)) {
                throw new InvalidDataException("Cannot convert json to array.");
            }
            $this->vegetableValidator->validate($vegetableDataRow);
        } catch (InvalidDataException $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $vegetable = $this->denormalizer->denormalize($vegetableDataRow, Vegetable::class);
        if (!($vegetable instanceof Vegetable)) {
            throw new InvalidDataException("Cannot convert json to Vegetable object.");
        }
        $this->vegetableRepository->save($vegetable);

        return $this->json($vegetableDataRow, Response::HTTP_CREATED);
    }
}
