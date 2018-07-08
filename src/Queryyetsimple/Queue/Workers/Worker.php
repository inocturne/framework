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

namespace Leevel\Queue\Workers;

use Clio\Console;
use PHPQueue\Worker as PHPQueueWorker;

/**
 * 基类 worker.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2017.05.11
 *
 * @version 1.0
 */
abstract class Worker extends PHPQueueWorker
{
    /**
     * 运行任务
     *
     * @param \Leevel\Queue\Jobs\IJob $job
     */
    public function runJob($job)
    {
        parent::runJob($job);

        $this->formatMessage(sprintf('Trying do run job %s.', $job->getName()));

        $job->handle();

        $this->formatMessage(sprintf('Job %s is done.'.'', $job->getName()));
        $this->formatMessage('Starting the next. ');

        $this->result_data = $job->data;
    }

    /**
     * 格式化输出消息.
     *
     * @param string $message
     *
     * @return string
     */
    protected function formatMessage($message)
    {
        Console::stdout(
            sprintf('[%s]', date('H:i:s')).$message.PHP_EOL
        );
    }
}
