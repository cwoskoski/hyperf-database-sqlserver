<?php

declare(strict_types=1);

namespace Hyperf\Database\Sqlsrv\Query\Processors;

use Exception;
use Hyperf\Database\Connection;
use Hyperf\Database\Query\Builder;
use Hyperf\Database\Query\Processors\Processor;

class SqlServerProcessor extends Processor
{
    /**
     * Process an "insert get ID" query.
     *
     * @param string      $sql
     * @param array       $values
     * @param null|string $sequence
     *
     * @throws Exception
     */
    public function processInsertGetId(Builder $query, $sql, $values, $sequence = null): int
    {
        /** @var Connection $connection */
        $connection = $query->getConnection();

        $connection->insert($sql, $values);

        if (true === $connection->getConfig('odbc')) {
            $id = $this->processInsertGetIdForOdbc($connection);
        } else {
            $id = $connection->getPdo()->lastInsertId();
        }

        return is_numeric($id) ? (int) $id : $id;
    }

    /**
     * Process the results of a column listing query.
     */
    public function processColumnListing(array $results): array
    {
        return array_map(function ($result) {
            return ((object) $result)->name;
        }, $results);
    }

    /**
     * Process an "insert get ID" query for ODBC.
     *
     * @throws Exception
     */
    protected function processInsertGetIdForOdbc(Connection $connection): int
    {
        $result = $connection->selectFromWriteConnection(
            'SELECT CAST(COALESCE(SCOPE_IDENTITY(), @@IDENTITY) AS int) AS insertid'
        );

        if (!$result) {
            throw new Exception('Unable to retrieve lastInsertID for ODBC.');
        }

        $row = $result[0];

        return is_object($row) ? $row->insertid : $row['insertid'];
    }
}
