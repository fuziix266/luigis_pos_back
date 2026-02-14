<?php

namespace Orders\Service\Factory;

use Orders\Service\OrderService;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class OrderServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): OrderService
    {
        return new OrderService($container->get(AdapterInterface::class));
    }
}
