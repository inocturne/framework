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

namespace Leevel\Event;

use Closure;
use InvalidArgumentException;
use SplObserver;
use SplSubject;

/**
 * 观察者角色 observer.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2017.06.23
 * @see http://php.net/manual/zh/class.splobserver.php
 *
 * @version 1.0
 */
class Observer implements SplObserver
{
    /**
     * 观察者目标角色 subject.
     *
     * @var \SplSubject
     */
    protected $subject;

    /**
     * 观察者实现.
     *
     * @var \Closure
     */
    protected $handle;

    /**
     * 构造函数.
     *
     * @param \Closure $handle
     */
    public function __construct(Closure $handle = null)
    {
        $this->handle = $handle;
    }

    /**
     * 观察者实现.
     *
     * @param array ...$args
     */
    public function __invoke(...$args)
    {
        call_user_func($this->handle, ...$args);
    }

    /**
     * {@inheritdoc}
     */
    public function update(SplSubject $subject): void
    {
        if (method_exists($this, 'handle')) {
            $handle = [$this, 'handle'];
        } elseif ($this->handle) {
            $handle = [$this, '__invoke'];
        } else {
            $handle = null;
        }

        if (!is_callable($handle)) {
            throw new InvalidArgumentException(
                sprintf('Observer %s must has handle method.', get_class($this))
            );
        }

        $subject->container->call($handle, $subject->notifyArgs);
    }
}
