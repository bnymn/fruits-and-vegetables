<?php
declare(strict_types=1);

namespace App\Tests\Infrastructure\Controller;

use App\Application\DataImport\CombinedDataImporter;
use App\Application\Exception\InvalidDataException;
use App\Domain\Repository\FruitRepositoryInterface;
use App\Domain\Repository\VegetableRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FruitControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    private CombinedDataImporter $combinedDataImporter;

    private FruitRepositoryInterface $fruitRepository;

    private VegetableRepositoryInterface $vegetableRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $container = static::getContainer();

        /** @var CombinedDataImporter $dataImporter */
        $dataImporter = $container->get(CombinedDataImporter::class);
        $this->combinedDataImporter = $dataImporter;

        /** @var VegetableRepositoryInterface $vegetableRepository */
        $vegetableRepository = $container->get(VegetableRepositoryInterface::class);
        $this->vegetableRepository = $vegetableRepository;

        /** @var FruitRepositoryInterface $fruitRepository */
        $fruitRepository = $container->get(FruitRepositoryInterface::class);
        $this->fruitRepository = $fruitRepository;
    }

    #[Test]
    public function shouldReturnAllFruits(): void
    {
        // Arrange
        $this->combinedDataImporter->import('var/imports/request.json');

        // Act
        $this->client->request('GET', '/fruits/');

        // Assert (1)
        $this->assertResponseIsSuccessful();

        // Assert (2)
        $this->assertResponseHeaderSame('content-type', 'application/json');

        // Assert (3)
        $response = $this->client->getResponse()->getContent();
        $this->assertIsString($response);
        $data = json_decode($response, true);
        $this->assertIsArray($data);

        // Assert (4)
        $numberOfFruits = 10;
        $this->assertCount($numberOfFruits, $data);

        // Assert (5)
        $firstFruit = $data[0];
        $this->assertArrayHasKey('id', $firstFruit);

        // Assert (6)
        $this->assertEquals(2, $firstFruit['id']);

        // After
        $this->vegetableRepository->deleteAll();
        $this->fruitRepository->deleteAll();
    }

    #[Test]
    public function shouldCreateFruit(): void
    {
        // Act
        $requestBody = json_encode([
            'id' => '1',
            'name' => 'First created fruit',
            'quantity' => '1',
        ]);
        $this->assertIsString($requestBody);
        $this->client->request('POST', '/fruits/', [], [], [], $requestBody);

        // Assert (1)
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        // Assert (2)
        $content = $this->client->getResponse()->getContent();
        $this->assertIsString($content);
        $this->assertJson($content);

        // Assert (3)
        $responseData = json_decode($content, true);
        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('id', $responseData); // Assert that an ID was returned

        // Assert (4)
        $this->assertEquals('First created fruit', $responseData['name']);

        // Assert (5)
        $this->assertEquals(1, $responseData['quantity']);

        // After
        $this->fruitRepository->deleteAll();
    }

    #[Test]
    public function shouldNotCreateWithInvalidData(): void
    {
        // Act (1)
        $requestBody = json_encode([]);
        $this->assertIsString($requestBody);
        $this->client->request('POST', '/fruits/', [], [], [], $requestBody);

        // Assert (1)
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertSame('"Attribute \"id\" does not have numeric value. Invalid value: \"\"."', $this->client->getResponse()->getContent());

        // Act (2)
        $requestBody = json_encode([
            'id' => 1
        ]);
        $this->assertIsString($requestBody);
        $this->client->request('POST', '/fruits/', [], [], [], $requestBody);

        // Assert (2)
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());

        // Act (3)
        $requestBody = json_encode([
            'id' => 1,
            'name' => 'First created fruit',
        ]);
        $this->assertIsString($requestBody);
        $this->client->request('POST', '/fruits/', [], [], [], $requestBody);

        // Assert (3)
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }
}
