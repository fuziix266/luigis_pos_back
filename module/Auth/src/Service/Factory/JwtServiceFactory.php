<?php

namespace Auth\Service\Factory;

use Auth\Service\JwtService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class JwtServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): JwtService
    {
        $config = $container->get('config');
        $jwtConfig = $config['jwt'] ?? [];
        return new JwtService(
            $jwtConfig['secret'] ?? 'default_secret',
            $jwtConfig['expiration'] ?? 86400
        );
    }
}
