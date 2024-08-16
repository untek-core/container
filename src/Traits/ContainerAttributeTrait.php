<?php

namespace Untek\Core\Container\Traits;

use Psr\Container\ContainerInterface;
use Untek\Core\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

trait ContainerAttributeTrait
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
