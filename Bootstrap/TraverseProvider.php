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

namespace Leevel\Bootstrap\Bootstrap;

use Leevel\Kernel\IProject;

/**
 * 遍历服务提供者.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2018.05.02
 *
 * @version 1.0
 */
class TraverseProvider
{
    /**
     * 响应.
     *
     * @param \Leevel\Kernel\IProject $project
     */
    public function handle(IProject $project): void
    {
        $project->registerProviders();

        $project->bootstrapProviders();
    }
}
