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
 * (c) 2010-2018 http://queryphp.com All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leevel\Auth\Middleware;

use Closure;
use Leevel\Auth\AuthException;
use Leevel\Auth\Manager;
use Leevel\Http\IRequest;

/**
 * Auth 中间件.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2018.11.05
 *
 * @version 1.0
 */
class Auth
{
    /**
     * auth 管理.
     *
     * @var \Leevel\Auth\Manager
     */
    protected $manager;

    /**
     * 构造函数.
     *
     * @param \Leevel\Auth\Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * 请求
     *
     * @param \Closure              $next
     * @param \Leevel\Http\IRequest $request
     */
    public function handle(Closure $next, IRequest $request): void
    {
        if (!$this->manager->isLogin()) {
            throw new AuthException('User authorization failed.');
        }

        $next($request);
    }
}
