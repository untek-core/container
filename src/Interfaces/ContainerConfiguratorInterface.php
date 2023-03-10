<?php

namespace Untek\Core\Container\Interfaces;

use Psr\Container\ContainerInterface;

/**
 * Конфигуратор контейнера.
 */
interface ContainerConfiguratorInterface
{

    /**
     * Получить DI-контейнер.
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface;

    /**
     * Поиск и автовайринг классов из списка директорий.
     *
     * @param array $dirs
     */
    public function importFromDir(array $dirs): void;

    /**
     * Объявить синглтон
     *
     * @param $abstract
     * @param $concrete
     */
    public function singleton($abstract, $concrete): void;

    /**
     * Объявить класс
     *
     * @param $abstract
     * @param $concrete
     * @param bool $shared
     */
    public function bind($abstract, $concrete, bool $shared = false): void;

    /**
     * @deprecated
     */
    public function bindContainerSingleton(): void;

    /**
     * Объявить алиас
     *
     * @param $abstract
     * @param $alias
     */
    public function alias($abstract, $alias): void;
}
