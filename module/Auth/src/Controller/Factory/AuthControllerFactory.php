<?php

namespace Auth\Controller\Factory;

use Auth\Controller\AuthController;
use Auth\Model\UserTable;
use Auth\Service\JwtService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class AuthControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuthController
    {
        return new AuthController(
            $container->get(UserTable::class),
            $container->get(JwtService::class)
        );
    }
}
