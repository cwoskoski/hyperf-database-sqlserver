<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Hyperf\Database\Sqlsrv;

use Doctrine\DBAL\Driver\PDOSqlsrv\Driver as DoctrineDriver;
use Hyperf\Database\Connection;
use Hyperf\Database\Sqlsrv\Query\Processors\SqlServerProcessor as SqlsrvQueryProcessor;
use Hyperf\Database\Sqlsrv\Schema\Grammars\SqlServerGrammar as SqlsrvSchemaGrammar;
use Hyperf\Database\Sqlsrv\Query\Grammars\SqlServerGrammar as SqlsrvQueryGrammar;
use Hyperf\Database\Sqlsrv\Schema\SqlServerBuilder as SqlsrvSchemaBuilder;
use PDO;

class SqlServerConnection extends Connection
{
    /**
     * Execute a Closure within a transaction.
     *
     * @param  \Closure  $callback
     * @param  int  $attempts
     * @return mixed
     *
     * @throws \Exception|\Throwable
     */
    public function transaction(\Closure $callback, $attempts = 1)
    {
        for ($a = 1; $a <= $attempts; $a++) {
            if ($this->getDriverName() === 'sqlsrv') {
                return parent::transaction($callback);
            }

            $this->getPdo()->exec('BEGIN TRAN');

            // We'll simply execute the given callback within a try / catch block
            // and if we catch any exception we can rollback the transaction
            // so that none of the changes are persisted to the database.
            try {
                $result = $callback($this);

                $this->getPdo()->exec('COMMIT TRAN');
            }

                // If we catch an exception, we will roll back so nothing gets messed
                // up in the database. Then we'll re-throw the exception so it can
                // be handled how the developer sees fit for their applications.
            catch (Exception $e) {
                $this->getPdo()->exec('ROLLBACK TRAN');

                throw $e;
            } catch (Throwable $e) {
                $this->getPdo()->exec('ROLLBACK TRAN');

                throw $e;
            }

            return $result;
        }
    }

    /**
     * Get the default query grammar instance.
     *
     * @return SqlsrvQueryGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new SqlsrvQueryGrammar);
    }

    /**
     * Get a schema builder instance for the connection.
     *
     * @return SqlsrvSchemaBuilder
     */
    public function getSchemaBuilder()
    {
        if ($this->schemaGrammar === null) {
            $this->useDefaultSchemaGrammar();
        }

        return new SqlsrvSchemaBuilder($this);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return SqlsrvSchemaGrammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new SqlsrvSchemaGrammar);
    }

    /**
     * Get the default post processor instance.
     *
     * @return SqlsrvQueryProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new SqlsrvQueryProcessor;
    }

    /**
     * Get the Doctrine DBAL driver.
     *
     * @return \Doctrine\DBAL\Driver\PDOSqlsrv\Driver
     */
    protected function getDoctrineDriver()
    {
        return new DoctrineDriver;
    }
}
