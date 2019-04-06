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

use Leevel\Support\Fn;
use Leevel\Support\Str;

if (!function_exists('fn')) {
    /**
     * 自动导入函数.
     *
     * @param \Closure|string $call
     * @param array           $args
     * @param mixed           $fn
     *
     * @return mixed
     */
    function fn($fn, ...$args)
    {
        return (new Fn())($fn, ...$args);
    }
}

if (!function_exists('app')) {
    /**
     * 返回应用容器或者注入.
     *
     * @param null|string $service
     * @param array       $args
     *
     * @return \Leevel\Leevel\App|mixed
     */
    function app(?string $service = null, array $args = [])
    {
        return Leevel::app($service, $args);
    }
}

if (!function_exists('__')) {
    /**
     * 获取语言.
     *
     * @param string $text
     * @param array  $arr
     *
     * @return string
     */
    function __(string $text, ...$arr): string
    {
        return I18n::__($text, ...$arr);
    }
}

if (!function_exists('dump')) {
    /**
     * 调试变量.
     *
     * @param mixed $var
     * @param array $moreVars
     *
     * @return mixed
     */
    function dump($var, ...$moreVars)
    {
        return Leevel::dump($var, ...$moreVars);
    }
}

if (!function_exists('dd')) {
    /**
     * 调试变量并中断.
     *
     * @param mixed $var
     * @param array $moreVars
     */
    function dd($var, ...$moreVars): void
    {
        Leevel::dd($var, ...$moreVars);
    }
}

if (!function_exists('drr')) {
    /**
     * 调试 RoadRunner 变量.
     *
     * @param mixed $var
     * @param array $moreVars
     *
     * @return mixed
     */
    function drr($var, ...$moreVars)
    {
        return Leevel::drr($var, ...$moreVars);
    }
}

if (!function_exists('backtrace')) {
    /**
     * 调试栈信息.
     */
    function backtrace(): void
    {
        Leevel::backtrace();
    }
}

if (!function_exists('spl_object_id')) {
    /**
     * 兼容 7.2 spl_object_id.
     *
     * @param object $obj
     *
     * @return string
     */
    function spl_object_id($obj): string
    {
        return spl_object_hash($obj);
    }
}
/**
 * 函数库.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2016.11.26
 *
 * @version 1.0
 */
class Leevel
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
        $fn = '\\Leevel\\Leevel\\Helper\\'.Str::unCamelize($method);

        return (new Fn())($fn, ...$args);
    }
}
