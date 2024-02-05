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

use Hyperf\Coroutine\WaitGroup;
use Hyperf\Database\Sqlsvr\SqlServerConnection;
use Hyperf\Database\Sqlsvr\Connectors\SqlServerConnector;
use Swoole\Event;
use function Hyperf\Coroutine\go;
use function Hyperf\Support\env;

\error_reporting(E_ALL & ~E_NOTICE);
\chdir(\dirname(__DIR__));

require 'vendor/autoload.php';

\Swoole\Runtime::enableCoroutine();

$now = microtime(true);

\Swoole\Coroutine\run(function() use ($now) {
    $config = [
        'host' => 'db',
        'port' => 1433,
        'database' => 'master',
        'username' => 'sa',
        'password' => 'BdTAekTAdR7cbvpu'
    ];

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

var_dump(microtime(true) - $now);

Event::wait();