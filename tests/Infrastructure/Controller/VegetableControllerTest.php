<?php
declare(strict_types=1);

namespace App\Tests\Infrastructure\Controller;

use App\Application\DataImport\CombinedDataImporter;
use App\Domain\Repository\FruitRepositoryInterface;
use App\Domain\Repository\VegetableRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class VegetableControllerTest extends WebTestCase
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
    public function indexShouldReturnAllVegetables(): void
    {
        // Arrange
        $this->combinedDataImporter->import('var/imports/request.json');

        // Act
        $this->client->request('GET', '/vegetables/');

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/json');

        $response = $this->client->getResponse()->getContent();
        $this->assertNotFalse($response);

        $data = json_decode($response, true);
        $this->assertIsArray($data);

        $numberOfVegetables = 10;
        $this->assertCount($numberOfVegetables, $data);

        $firstVegetable = $data[0];
        $this->assertArrayHasKey('id', $firstVegetable);
        $this->assertEquals(1, $firstVegetable['id']);

        // After
        $this->vegetableRepository->deleteAll();
        $this->fruitRepository->deleteAll();
    }


    #[Test]
    public function shouldCreateVegetable(): void
    {
        // Act
        $requestBody = json_encode([
            'id' => '1',
            'name' => 'First created vegetable',
            'quantity' => '1',
        ]);
        $this->assertIsString($requestBody);
        $this->client->request('POST', '/vegetables/', [], [], [],  $requestBody);

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
        $this->assertEquals('First created vegetable', $responseData['name']);

        // Assert (5)
        $this->assertEquals(1, $responseData['quantity']);

        // After
        $this->vegetableRepository->deleteAll();
    }

    #[Test]
    public function shouldNotCreateWithInvalidData(): void
    {
        $this->client->request('POST', '/vegetables/', [], [], [], json_encode([]) ?: '');

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());

        // Act (2)
        $this->client->request('POST', '/vegetables/', [], [], [], json_encode([
            'id' => 1
        ]) ?: '');

        // Assert (2)
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());

        // Act (3)
        $this->client->request('POST', '/vegetables/', [], [], [], json_encode([
            'id' => 1,
            'name' => 'First created vegetable',
        ]) ?: '');

        // Assert (3)
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }
}
