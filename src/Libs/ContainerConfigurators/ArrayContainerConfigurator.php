<?php

namespace Untek\Core\Container\Libs\ContainerConfigurators;

use Untek\Core\Container\Libs\Container;
use Psr\Container\ContainerInterface;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Container\Interfaces\ContainerConfiguratorInterface;

class ArrayContainerConfigurator implements ContainerConfiguratorInterface
{

    private $config = [];
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function importFromDir(array $dirs): void {
        foreach ($dirs as &$dir) {
            $dir = realpath($dir);
        }
//        dd($dirs);
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function singleton($abstract, $concrete): void
    {
        $this->config['singletons'][$abstract] = $concrete;
//        $this->container->singleton($abstract, $concrete);
    }

    public function bind($abstract, $concrete, bool $shared = false): void
    {
        $this->config['definitions'][$abstract] = $concrete;
//        $this->container->bind($abstract, $concrete, $shared);
    }

    public function bindContainerSingleton(): void
    {
//        $this->container->singleton(ContainerInterface::class, Container::class);
        /*$this->container->singleton(Container::class, function () {
            return $this->container;
        });*/
    }

    public function alias($abstract, $alias): void
    {
//        $this->container->alias($abstract, $alias);
        $this->config['aliases'][$abstract] = $concrete;
    }
}
