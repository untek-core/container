<?php

namespace Untek\Core\Container\Traits;

use Psr\Container\ContainerInterface;

trait ContainerAwareTrait
{

    use ContainerAwareAttributeTrait;

    public function __construct(ContainerInterface $container = null)
    {
        $this->setContainer($container);
    }
}
