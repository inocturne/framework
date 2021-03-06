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

namespace Leevel\Router;

use Leevel\Kernel\Proxy\App;
use Leevel\Router\Proxy\Url;

/**
 * openapi 路由扫描.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2018.04.18
 *
 * @version 1.0
 */
class ScanRouter
{
    /**
     * openapi 路由分析.
     *
     * @var \Leevel\Router\OpenApiRouter
     */
    protected $openApiRouter;

    /**
     * 构造函数.
     *
     * @param \Leevel\Router\MiddlewareParser $middlewareParser
     */
    public function __construct(MiddlewareParser $middlewareParser)
    {
        $this->openApiRouter = new OpenApiRouter(
            $middlewareParser, $this->getDomain()
        );

        foreach ([$this->routePath(), $this->appPath()] as $path) {
            $this->openApiRouter->addScandir($path);
        }
    }

    /**
     * 响应.
     *
     * @return array
     */
    public function handle(): array
    {
        return $this->openApiRouter->handle();
    }

    /**
     * 获取顶级域名.
     *
     * @return string
     */
    protected function getDomain(): string
    {
        return Url::getDomain();
    }

    /**
     * 获取应用目录.
     *
     * @return string
     */
    protected function appPath(): string
    {
        return App::appPath();
    }

    /**
     * 获取路由目录.
     *
     * @return string
     */
    protected function routePath(): string
    {
        return App::path('router');
    }
}
