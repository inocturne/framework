<?php

declare(strict_types=1);

/*
 * This file is part of the ************************ package.
 * _____________                           _______________
 *  ______/     \__  _____  ____  ______  / /_  _________
 *   ____/ __   / / / / _ \/ __`\/ / __ \/ __ \/ __ \___
 *    __/ / /  / /_/ /  __/ /  \  / /_/ / / / / /_/ /__
 *      \_\ \_/\____/\___/_/   / / .___/_/ /_/ .___/
 *         \_\                /_/_/         /_/
 *
 * The PHP Framework For Code Poem As Free As Wind. <Query Yet Simple>
 * (c) 2010-2019 http://queryphp.com All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leevel\Auth\Proxy;

use Leevel\Auth\Manager;
use Leevel\Di\Container;

/**
 * 代理 auth.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2017.09.08
 *
 * @version 1.0
 */
class Auth implements IAuth
{
    /**
     * call.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public static function __callStatic(string $method, array $args)
    {
        return self::connect()->{$method}(...$args);
    }

    /**
     * 用户是否已经登录.
     *
     * @return bool
     */
    public static function isLogin(): bool
    {
        return self::connect()->isLogin();
    }

    /**
     * 获取登录信息.
     *
     * @return array
     */
    public static function getLogin(): array
    {
        return self::connect()->getLogin();
    }

    /**
     * 登录写入数据.
     *
     * @param array $data
     * @param int   $loginTime
     */
    public static function login(array $data, int $loginTime = 0): void
    {
        self::connect()->login($data, $loginTime);
    }

    /**
     * 登出.
     */
    public static function logout(): void
    {
        self::connect()->logout();
    }

    /**
     * 设置认证名字.
     *
     * @param string $tokenName
     */
    public static function setTokenName(string $tokenName): void
    {
        self::connect()->setTokenName($tokenName);
    }

    /**
     * 取得认证名字.
     *
     * @return string
     */
    public static function getTokenName(): string
    {
        return self::connect()->getTokenName();
    }

    /**
     * auths 服务
     *
     * @return \Leevel\Auth\Manager
     */
    protected static function connect(): Manager
    {
        return Container::singletons()->make('auths');
    }
}