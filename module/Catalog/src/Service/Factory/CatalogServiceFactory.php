<?php

namespace Catalog\Service\Factory;

use Catalog\Service\CatalogService;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class CatalogServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): CatalogService
    {
        return new CatalogService($container->get(AdapterInterface::class));
    }
}
