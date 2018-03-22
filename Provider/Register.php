<?php
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
namespace Queryyetsimple\Log\Provider;

use Queryyetsimple\{
    Log\Manager,
    Di\Provider
};

/**
 * log 服务提供者
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2017.05.12
 * @version 1.0
 */
class Register extends Provider
{

    /**
     * 注册服务
     *
     * @return void
     */
    public function register()
    {
        $this->logs();
        $this->log();
        $this->middleware();
    }

    /**
     * 可用服务提供者
     *
     * @return array
     */
    public static function providers()
    {
        return [
            'logs' => [
                'Queryyetsimple\Log\Manager',
                'Qys\Log\Manager'
            ],
            'log' => [
                'Queryyetsimple\Log\Log',
                'Queryyetsimple\Log\ILog',
                'Qys\Log\Log',
                'Qys\Log\ILog'
            ],
            'Queryyetsimple\Log\Middleware\Log'
        ];
    }

    /**
     * 注册 logs 服务
     *
     * @return void
     */
    protected function logs()
    {
        $this->singleton('logs', function ($project) {
            return new Manager($project);
        });
    }

    /**
     * 注册 log 服务
     *
     * @return void
     */
    protected function log()
    {
        $this->singleton('log', function ($project) {
            return $project['logs']->connect();
        });
    }

    /**
     * 注册 middleware 服务
     *
     * @return void
     */
    protected function middleware()
    {
        $this->singleton('Queryyetsimple\Log\Middleware\Log');
    }
}
