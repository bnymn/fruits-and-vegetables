<?php
// config/services.php
namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Application\DataImport\CombinedDataImport\Transformer;
use App\Application\DataImport\CombinedDataImport\Validator;
use App\Application\DataImport\CombinedDataImport\Writer;
use App\Application\DataImport\CombinedDataImporter;
use App\Application\DataImport\ReaderInterface;
use App\Domain\Repository\FruitRepositoryInterface;
use App\Domain\Repository\VegetableRepositoryInterface;
use App\Infrastructure\DataImport\Reader;
use App\Infrastructure\Repository\InMemory\FruitRepository;
use App\Infrastructure\Repository\InMemory\VegetableRepository;

return function(ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
    ;

    // makes classes in src/ available to be used as services
    // this creates a service per class whose id is the fully-qualified class name
    $services
        ->load('App\\', '../src/')
        ->exclude('../src/{DependencyInjection,Domain,Kernel.php}');

    $services
        ->set(FruitRepositoryInterface::class)
        ->class(FruitRepository::class)
        ->public();

    $services
        ->set(VegetableRepositoryInterface::class)
        ->class(VegetableRepository::class)
        ->public();

    $services
        ->set(ReaderInterface::class)
        ->class(Reader::class)
        ->public();

    $services
        ->set(CombinedDataImporter::class)
        ->args([
            '$reader' => service(Reader::class),
            '$validator' => service(Validator::class),
            '$transformer' => service(Transformer::class),
            '$writer' => service(Writer::class),
        ])
        ->public();

    // order is important in this file because service definitions
    // always *replace* previous ones; add your own service configuration below
};
