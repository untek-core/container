<?php

namespace Untek\Core\Container\Traits;

use Psr\Container\ContainerInterface;
use Untek\Core\Contract\Common\Exceptions\ReadOnlyException;

trait ContainerAwareStaticAttributeTrait
{

    private static $container = null;

    public static function setContainer(ContainerInterface $container): void
    {
        /*if (self::$container) {
            throw new ReadOnlyException();
        }*/
        self::$container = $container;
    }

    /**
     * @return ContainerInterface|null
     */
    public static function getContainer(): ?ContainerInterface
    {
        return self::$container;
    }
}
