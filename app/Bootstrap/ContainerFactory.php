<?php

declare(strict_types=1);

namespace App\Bootstrap;

use App\Bootstrap\Services\AuthService;
use App\Bootstrap\Services\AuthInterface;

use App\Bootstrap\Services\UserService;
use App\Bootstrap\Services\UserInterface;

use App\Bootstrap\Services\AccessService;
use App\Bootstrap\Services\AccessInterface;

use App\Bootstrap\Services\RequestIdService;
use App\Bootstrap\Services\RequestIdInterface;

use Hleb\Constructor\Attributes\Dependency;
use Hleb\Constructor\Containers\BaseContainerFactory;
use Hleb\Reference\DbInterface;

#[Dependency]
final class ContainerFactory extends BaseContainerFactory
{
    /**
     * Getting the same class object (singleton).
     *
     * Получение одного и того же объекта класса (singleton).
     */
    public static function getSingleton(string $id): mixed
    {
        // Here objects are created as singletons according to the interface (or class) they correspond to.
        // Здесь создаются объекты как singleton согласно соответствию с интерфейсом (или классом).
        self::has($id) or self::$singletons[$id] = match ($id) {

            // An example of a custom service for getting a request ID.
            // Пример пользовательского сервиса для получения идентификатора запроса.
            RequestIdInterface::class => new RequestIdService(),

            AuthInterface::class => new AuthService(),
            UserInterface::class => new UserService(),
            AccessInterface::class => new AccessService(),

            default => null
        };

        return self::$singletons[$id];
    }

    /**
     * A system method that resets class data on an asynchronous request.
     * This method is called upon completion of each asynchronous request.
     * If some data should not change during an asynchronous request, then you can exclude them.
     * For example:
     *
     * Системный метод, при помощи которого сбрасываются данные класса при асинхронном запросе.
     * Этот метод вызывается при завершении каждого асинхронного запроса.
     * Если какие-то данные не должны меняться при асинхронном запросе, то можно их исключить.
     * Например:
     *
     *  ```php
     *
     * foreach(self::$singletons as $key => $value) {
     *    if ($key === RequestIdInterface::class) continue;
     *    unset(self::$singletons[$key]);
     * }
     * ```
     *
     * @inheritDoc
     */
    public static function rollback(): void
    {
        self::$singletons = [];

        // The connection to the database is reset with each asynchronous request.
        // Сбрасывается подключение к БД при каждом асинхронном запросе.
        BaseContainer::instance()->get(DbInterface::class)::rollback();
    }
}
