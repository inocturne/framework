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

namespace Leevel\Protocol\Process;

/**
 * Swoole 自动进程抽象类.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2018.12.15
 *
 * @version 1.0
 */
abstract class Process
{
    /**
     * 进程名字.
     *
     * @var string
     */
    protected $name;

    /**
     * 获取进程名称.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
