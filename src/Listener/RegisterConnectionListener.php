<?php
declare(strict_types=1);

namespace Hyperf\Database\Sqlsvr\Listener;

use Hyperf\Contract\ContainerInterface;
use Hyperf\Database\Sqlsvr\SqlsvrConnection;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;

final class RegisterConnectionListener implements ListenerInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function listen(): array
    {
        return [
            BootApplication::class,
        ];
    }

    public function process(object $event)
    {
        \Hyperf\Database\Connection::resolverFor('sqlsvr', function($connection, $database, $prefix, $config){
            return new SqlsvrConnection($connection, $database, $prefix, $config);
        });
    }
}