<?php

namespace Hyperf\Database\Sqlsrv\Task;

use Hyperf\Database\Sqlsrv\SqlServerConnection;
use Hyperf\DbConnection\Db;
use Hyperf\Task\Annotation\Task;

class SqlServerTask
{
    #[Task]
    public function select(string $connection, string $query, array $bindings = [], bool $useReadPdo = true): array
    {
        /** @var SqlServerConnection $conn */
        $conn = Db::connection($connection);

        return $conn->setIsTaskEnvironment()->select($query, $bindings, $useReadPdo);
    }

    #[Task]
    public function statement(string $connection, string $query, array $bindings = []): bool
    {
        /** @var SqlServerConnection $conn */
        $conn = Db::connection($connection);

        return $conn->setIsTaskEnvironment()->statement($query, $bindings);
    }

    #[Task]
    public function affectingStatement(string $connection, string $query, array $bindings = []): int
    {
        /** @var SqlServerConnection $conn */
        $conn = Db::connection($connection);

        return $conn->setIsTaskEnvironment()->affectingStatement($query, $bindings);
    }

    #[Task]
    public function unprepared(string $connection, string $query): bool
    {
        /** @var SqlServerConnection $conn */
        $conn = Db::connection($connection);

        return $conn->setIsTaskEnvironment()->unprepared($query);
    }
}
