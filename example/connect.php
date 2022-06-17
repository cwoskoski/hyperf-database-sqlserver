<?php

/**
 * This file is part of the cvtrust/aetna-sutter-eligibility.
 * (c) 2018-2018 California's Valued Trust <itdept@cvtrust.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Hyperf\Database\Sqlsvr;

use Hyperf\Database\Postgres\Connectors\PostgresConnector;
use Hyperf\Database\Postgres\PostgresConnection;
use Hyperf\Database\Sqlsvr\Connectors\SqlServerConnector;
use Swoole\Event;

\error_reporting(E_ALL & ~E_NOTICE);
\chdir(\dirname(__DIR__));

require 'vendor/autoload.php';

\Swoole\Runtime::enableCoroutine();

\Swoole\Coroutine\run(function () {
    $config = [
        'host' => 'db',
        'port' => 1433,
        'database' => 'master',
        'username' => env('db_user'),
        'password' => env('db_password')
    ];

    $now = microtime(true);

    go(function() use($config) {
        $now = microtime(true);
        $connector = new SqlServerConnector();
        $conn = $connector->connect($config);
        $stmt = $conn->query("WAITFOR DELAY '00:00:02'");
        var_dump(microtime(true) - $now);
    });

    go(function() use($config) {
        $now = microtime(true);
        $connector = new SqlServerConnector();
        $conn = $connector->connect($config);
        $stmt = $conn->query("WAITFOR DELAY '00:00:02'");
        var_dump(microtime(true) - $now);
    });

    var_dump(microtime(true) - $now);
});

Event::wait();
