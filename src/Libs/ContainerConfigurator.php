<?php

namespace Untek\Core\Container\Libs;

use Untek\Core\Container\Libs\Container;
use Psr\Container\ContainerInterface;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Core\Instance\Helpers\InstanceHelper;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\Container\Interfaces\ContainerConfiguratorInterface;
use Untek\Core\Container\Libs\ContainerConfigurators\IlluminateContainerConfigurator;

class ContainerConfigurator implements ContainerConfiguratorInterface
{

    private $drivers = [
        Container::class => [
            'class' => IlluminateContainerConfigurator::class,
        ]
    ];
    /** @var Container */
    private $container;
    private $configurator;
    private $config = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->configurator = $this->getContainerConfiguratorByContainer($container);
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function importFromDir(array $dirs): void {
        foreach ($dirs as &$dir) {
            $dir = realpath($dir);
        }
        if($dirs) {
            $this->configurator->importFromDir($dirs);
            $this->config['import'] = ArrayHelper::merge($this->config['import'] ?? [], $dirs);
        }
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function singleton($abstract, $concrete): void
    {
        $this->configurator->singleton($abstract, $concrete);
        $this->config['singletons'][$abstract] = $concrete;
    }

    public function bind($abstract, $concrete, bool $shared = false): void
    {
        $this->configurator->bind($abstract, $concrete, $shared);
        $this->config['definitions'][$abstract] = $concrete;
    }

    public function bindContainerSingleton(): void
    {
        $this->configurator->singleton(ContainerConfiguratorInterface::class, function () {
            return $this;
        });
        $this->configurator->bindContainerSingleton();
    }

    public function alias($abstract, $alias): void
    {
        $this->configurator->alias($abstract, $alias);
        $this->config['aliases'][$abstract] = $concrete;
    }

    private function getContainerConfiguratorByContainer(ContainerInterface $container): ContainerConfiguratorInterface
    {
        /** @var ContainerConfiguratorInterface $configurator */
        foreach ($this->drivers as $containerClass => $configuratorDefinition) {
            if ($container instanceof $containerClass) {
                $configurator = InstanceHelper::create($configuratorDefinition['class'], [ContainerInterface::class => $container]);
                //return new $configuratorDefinition($container);
            }
        }
        if (!isset($configurator)) {
            throw new NotFoundException('Not found driver for ContainerConfigurator');
        }
        return $configurator;
    }
}
