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

namespace Leevel\Session;

use Leevel\Cache\Redis as CacheRedis;
use Leevel\Cache\Redis\PhpRedis;

/**
 * session.redis.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2017.06.05
 *
 * @version 1.0
 */
class Redis extends Session implements ISession
{
    /**
     * 配置.
     *
     * @var array
     */
    protected $option = [
        'id'         => null,
        'name'       => null,
        'host'       => '127.0.0.1',
        'port'       => 6379,
        'password'   => '',
        'select'     => 0,
        'timeout'    => 0,
        'persistent' => false,
        'serialize'  => true,
        'expire'     => null,
    ];

    /**
     * 构造函数.
     *
     * @param array $option
     */
    public function __construct(array $option = [])
    {
        $this->option = array_merge($this->option, $option);

        parent::__construct($this->createCache());
    }

    /**
     * redis 缓存.
     *
     * @return \Leevel\Cache\Redis
     */
    public function createCache(): CacheRedis
    {
        return new CacheRedis(new PhpRedis($this->option), $this->option);
    }
}
