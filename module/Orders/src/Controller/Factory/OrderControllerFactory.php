<?php

namespace Orders\Controller\Factory;

use Orders\Controller\OrderController;
use Orders\Service\OrderService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class OrderControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): OrderController
    {
        return new OrderController($container->get(OrderService::class));
    }
}
