<?php

declare(strict_types=1);

namespace Hyperf\Database\Sqlsrv;

use Hyperf\Database\Sqlsrv\Connectors\SqlServerConnector;
use Hyperf\Database\Sqlsrv\Listener\RegisterConnectionListener;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'dependencies' => [
                'db.connector.sqlsrv' => SqlServerConnector::class,
            ],
            'listeners' => [
                RegisterConnectionListener::class,
            ],
        ];
    }
}
