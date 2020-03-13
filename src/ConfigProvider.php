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

namespace Hyperf\Database\Postgres;

use Hyperf\Database\Postgres\Connectors\PostgresConnector;
use Hyperf\Database\Postgres\Listener\RegisterConnectionListener;

class ConfigProvider
{
    public function __invoke(): array
    {
        var_dump(__METHOD__);
        return [
            'dependencies' => [
                'db.connector.pgsql' => PostgresConnector::class,
            ],
            'listeners' => [
                RegisterConnectionListener::class
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
        ];
    }
}
