<?php

namespace Auth\Model;

use Laminas\Db\TableGateway\TableGatewayInterface;

class UserTable
{
    private TableGatewayInterface $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function findByUsername(string $username): ?User
    {
        $rowset = $this->tableGateway->select(['username' => $username]);
        $row = $rowset->current();
        return $row ?: null;
    }

    public function findById(int $id): ?User
    {
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        return $row ?: null;
    }

    public function fetchAll(): array
    {
        $resultSet = $this->tableGateway->select();
        $users = [];
        foreach ($resultSet as $row) {
            $users[] = $row;
        }
        return $users;
    }
}
