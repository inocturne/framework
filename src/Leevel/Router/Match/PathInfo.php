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

namespace Leevel\Router\Match;

use Leevel\Http\IRequest;
use Leevel\Router\IRouter;

/**
 * pathInfo 路由匹配.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2018.02.15
 *
 * @version 1.0
 */
class PathInfo extends Match implements IMatch
{
    /**
     * 匹配数据项.
     *
     * @param \Leevel\Router\IRouter $router
     * @param \Leevel\Http\IRequest  $request
     *
     * @return array
     */
    public function matche(IRouter $router, IRequest $request): array
    {
        $this->setRouterAndRequest($router, $request);

        return $this->matchMain();
    }

    /**
     * 主匹配.
     *
     * @return array
     */
    protected function matchMain(): array
    {
        // 匹配 PathInfo
        $path = $this->normalizePath($this->matchePathInfo());

        // 应用
        list($result, $path) = $this->matcheApp($path);

        // Middleware
        $result[IRouter::MIDDLEWARES] = $this->middlewares;

        if (!$path) {
            return $result;
        }

        // Mvc
        $result = array_merge($result, $this->matcheMvc($path));

        return $result;
    }

    /**
     * 格式化 PathInfo.
     *
     * @param array $pathInfo
     *
     * @return array
     */
    protected function normalizePath(string $pathInfo): array
    {
        $pathInfo = trim($pathInfo, '/');

        return $pathInfo ? explode('/', $pathInfo) : [];
    }

    /**
     * 匹配路由应用.
     *
     * @param array $path
     *
     * @return array
     */
    protected function matcheApp(array $path): array
    {
        $result = [];

        if ($path && $this->isFindApp($path[0])) {
            $result[IRouter::APP] = substr(array_shift($path), 1);
        }

        if ($restfulResult = $this->matcheRestful($path)) {
            return [array_merge($result, $restfulResult), []];
        }

        if (!$path) {
            $result[IRouter::CONTROLLER] = IRouter::DEFAULT_CONTROLLER;
        }

        return [$result, $path];
    }

    /**
     * 匹配路由 Mvc.
     *
     * @param array $path
     *
     * @return array
     */
    protected function matcheMvc(array $path): array
    {
        $result = [];

        if (1 === count($path)) {
            $result[IRouter::CONTROLLER] = array_pop($path);
        } else {
            if ($path) {
                $result[IRouter::ACTION] = array_pop($path);
            }

            if ($path) {
                $result[IRouter::CONTROLLER] = array_pop($path);
            }

            if ($path) {
                $result[IRouter::PREFIX] = $path;
            }
        }

        return $result;
    }

    /**
     * 是否找到 app.
     *
     * @param string $path
     *
     * @return bool
     */
    protected function isFindApp(string $path): bool
    {
        return 0 === strpos($path, ':');
    }

    /**
     * 匹配路由 Restful.
     *
     * @param array $path
     *
     * @return array
     */
    protected function matcheRestful(array $path): array
    {
        $restfulPath = implode('/', $path);
        $regex = '/^(\S+)\/('.IRouter::RESTFUL_REGEX.')(\/*\S*)$/';

        if (preg_match($regex, $restfulPath, $matches)) {
            $result[IRouter::CONTROLLER] = $matches[1];
            $result[IRouter::PARAMS][IRouter::RESTFUL_ID] = $matches[2];

            if ('' !== $matches[3]) {
                $result[IRouter::ACTION] = substr($matches[3], 1);
            }

            return $result;
        }

        return [];
    }
}
