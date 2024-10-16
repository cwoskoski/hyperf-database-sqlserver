<?php

namespace Hyperf\Database\Sqlsrv\Listener;

use Hyperf\Database\Sqlsrv\SqlServerConnection;
use Hyperf\Database\Connection;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;
use Psr\Container\ContainerInterface;

class RegisterConnectionListener implements ListenerInterface
{
    /**
     * Create a new connection factory instance.
     */
    public function __construct(protected ContainerInterface $container) {}

    public function listen(): array
    {
        return [
            BootApplication::class,
        ];
    }

    /**
     * register sqlserver need Connector and Connection.
     */
    public function process(object $event): void
    {
        Connection::resolverFor('sqlsrv', static function ($connection, $database, $prefix, $config) {
            return new SqlServerConnection($connection, $database, $prefix, $config);
        });
    }
}
