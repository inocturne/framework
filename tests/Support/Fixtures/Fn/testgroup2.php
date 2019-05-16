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

namespace Tests\Support\Fixtures\Fn;

/**
 * 使用方法.
 *
 * ``` php
 * echo f('Tests\\Support\\Fixtures\\Fn\\testgroup_fn1');
 * ```
 *
 * @return string
 */
function testgroup2_fn1(): string
{
    return 'g2:hello world';
}

/**
 * 使用方法.
 *
 * ``` php
 * echo f('Tests\\Support\\Fixtures\\Fn\\testgroup_fn2');
 * ```
 *
 * @return string
 */
function testgroup2_fn2(): string
{
    return 'g2:hello world2';
}

/**
 * Prevent duplicate loading.
 */
class testgroup2
{
}
