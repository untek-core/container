<?php

namespace Untek\Core\Container\Libs;

use Psr\Container\ContainerInterface;

class ContainerWrapper implements ContainerInterface {
    
    public function __construct(private ContainerInterface $container)
    {
    }

    public function get(string $id)
    {
        return $this->container->get($id);
    }

    public function has(string $id): bool
    {
        return $this->container->has($id);
    }
}
