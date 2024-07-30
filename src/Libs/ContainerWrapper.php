<?php

namespace Untek\Core\Container\Libs;

use Psr\Container\ContainerInterface;

class ContainerWrapper implements ContainerInterface {
    static $globalContainer;
    
    private ContainerInterface $container;
    
    public function __construct(?ContainerInterface $container = null)
    {
        if($container) {
            $this->container = $container;
            self::$globalContainer = $container;
        } elseif(self::$globalContainer) {
            $this->container = self::$globalContainer;
        } else {
            throw new \RuntimeException('Not found container!');
        }
    }

    public function get(string $id): mixed
    {
        return $this->container->get($id);
    }

    public function has(string $id): bool
    {
        return $this->container->has($id);
    }
}
