<?php

namespace Catalog\Controller\Factory;

use Catalog\Controller\CatalogController;
use Catalog\Service\CatalogService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class CatalogControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): CatalogController
    {
        return new CatalogController($container->get(CatalogService::class));
    }
}
