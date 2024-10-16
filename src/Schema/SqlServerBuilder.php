<?php

namespace Hyperf\Database\Sqlsrv\Schema;

use Hyperf\Database\Schema\Builder;

class SqlServerBuilder extends Builder
{
    /**
     * Create a database in the schema.
     */
    public function createDatabase(string $name): bool
    {
        return $this->connection->statement(
            $this->grammar->compileCreateDatabase($name, $this->connection)
        );
    }

    /**
     * Drop a database from the schema if the database exists.
     */
    public function dropDatabaseIfExists(string $name): bool
    {
        return $this->connection->statement(
            $this->grammar->compileDropDatabaseIfExists($name)
        );
    }

    /**
     * Drop all tables from the database.
     */
    public function dropAllTables(): void
    {
        $this->connection->statement($this->grammar->compileDropAllForeignKeys());

        $this->connection->statement($this->grammar->compileDropAllTables());
    }

    /**
     * Drop all views from the database.
     */
    public function dropAllViews(): void
    {
        $this->connection->statement($this->grammar->compileDropAllViews());
    }

    /**
     * Drop all tables from the database.
     */
    public function getAllTables(): array
    {
        return $this->connection->select(
            $this->grammar->compileGetAllTables()
        );
    }

    /**
     * Get all of the view names for the database.
     */
    public function getAllViews(): array
    {
        return $this->connection->select(
            $this->grammar->compileGetAllViews()
        );
    }
}
