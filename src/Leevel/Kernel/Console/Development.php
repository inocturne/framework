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

namespace Leevel\Kernel\Console;

use Leevel\Console\Command;
use Leevel\Console\Option;

/**
 * 开发模式清理系统缓存.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2018.09.04
 *
 * @version 1.0
 * @codeCoverageIgnore
 */
class Development extends Command
{
    /**
     * 命令名字.
     *
     * @var string
     */
    protected $name = 'development';

    /**
     * 命令行描述.
     *
     * @var string
     */
    protected $description = 'Clear all caches for development mode.';

    /**
     * 响应命令.
     */
    public function handle(): void
    {
        $this->line('Start to clears caches.');

        $this->callI18n();

        $this->callLog();

        $this->callOption();

        $this->callRouter();

        $this->callSession();

        $this->callView();

        $this->callAutoload();

        $this->line('');
        $this->info('Caches cleared successed.');
    }

    /**
     * 执行清理 i18n 缓存.
     */
    protected function callI18n(): void
    {
        $this->line('');
        $this->call('i18n:clear');
    }

    /**
     * 执行清理 log 缓存.
     */
    protected function callLog(): void
    {
        $this->line('');
        $this->call('log:clear');
    }

    /**
     * 执行清理 option 缓存.
     */
    protected function callOption(): void
    {
        $this->line('');
        $this->call('option:clear');
    }

    /**
     * 执行清理 router 缓存.
     */
    protected function callRouter(): void
    {
        $this->line('');
        $this->call('router:clear');
    }

    /**
     * 执行清理 session 缓存.
     */
    protected function callSession(): void
    {
        $this->line('');
        $this->call('session:clear');
    }

    /**
     * 执行清理 view 缓存.
     */
    protected function callView(): void
    {
        $this->line('');
        $this->call('view:clear');
    }

    /**
     * 执行 autoload 缓存.
     */
    protected function callAutoload(): void
    {
        $this->line('');
        $this->call('autoload', [
            '--composer' => $this->composer(),
            '--dev'      => true,
        ]);
    }

    /**
     * 取得 Composer 路径.
     *
     * @return string
     */
    protected function composer(): string
    {
        return $this->option('composer');
    }

    /**
     * 命令参数.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [];
    }

    /**
     * 命令配置.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            [
                'composer',
                null,
                Option::VALUE_OPTIONAL,
                'Where is composer.',
                'composer',
            ],
        ];
    }
}
