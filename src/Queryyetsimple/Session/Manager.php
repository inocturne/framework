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
namespace Queryyetsimple\Session;

use Queryyetsimple\Manager\Manager as Managers;

/**
 * manager 入口
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2017.02.15
 * @version 1.0
 */
class Manager extends Managers
{

    /**
     * 取得配置命名空间
     *
     * @return string
     */
    protected function getOptionNamespace()
    {
        return 'session';
    }

    /**
     * 创建连接对象
     *
     * @param object $connect
     * @return object
     */
    protected function createConnect($connect)
    {
        return new Session($connect, $this->getOptionCommon());
    }

    /**
     * 创建 cookie 缓存
     *
     * @param array $options
     * @return null
     */
    protected function makeConnectCookie($options = [])
    {
        return null;
    }

    /**
     * 创建 memcache 缓存
     *
     * @param array $options
     * @return \Queryyetsimple\Session\memcache
     */
    protected function makeConnectMemcache($options = [])
    {
        return new Memcache($this->getOption('memcache', $options));
    }

    /**
     * 创建 redis 缓存
     *
     * @param array $options
     * @return \Queryyetsimple\Session\redis
     */
    protected function makeConnectRedis($options = [])
    {
        return new Redis($this->getOption('redis', $options));
    }

    /**
     * 读取连接配置
     *
     * @param string $connect
     * @return array
     */
    protected function getOptionConnect($connect)
    {
        return $this->optionFilterNull(parent::getOptionConnect($connect));
    }
}
