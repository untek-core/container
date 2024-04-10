<?php

namespace Untek\Core\Container\Traits;

use Psr\Container\ContainerInterface;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Contract\Common\Exceptions\ReadOnlyException;

DeprecateHelper::hardThrow();

trait ContainerAwareStaticAttributeTrait
{

    private static ?ContainerInterface $container = null;

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
