<?php

namespace Auth\Model\Factory;

use Auth\Model\User;
use Auth\Model\UserTable;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class UserTableFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): UserTable
    {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new User());
        $tableGateway = new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
        return new UserTable($tableGateway);
    }
}
