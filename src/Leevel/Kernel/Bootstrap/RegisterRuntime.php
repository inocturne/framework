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

namespace Leevel\Kernel\Bootstrap;

use ErrorException;
use Exception;
use Leevel\Kernel\IApp;
use Leevel\Kernel\IRuntime;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

/**
 * 注册运行时异常.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2018.04.25
 *
 * @version 1.0
 */
class RegisterRuntime
{
    /**
     * 应用.
     *
     * @var \Leevel\Kernel\IApp
     */
    protected $app;

    /**
     * 响应.
     *
     * @param \Leevel\Kernel\IApp $app
     */
    public function handle(IApp $app): void
    {
        $this->app = $app;

        $test = 2 === func_num_args();

        if (!$test) {
            // @codeCoverageIgnoreStart
            error_reporting(E_ALL);

            set_error_handler([$this, 'setErrorHandle']);

            set_exception_handler([$this, 'setExceptionHandler']);

            register_shutdown_function([$this, 'registerShutdownFunction']);

            if ('production' === $app->environment()) {
                ini_set('display_errors', 'Off');
            }
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * 设置错误句柄.
     *
     * @param int    $code
     * @param string $description
     * @param string $file
     * @param int    $line
     * @param mixed  $context
     */
    public function setErrorHandle(int $code, string $description, ?string $file = null, ?int $line = null, $context = null): void
    {
        // 根据 error_reporing 等级来确定是否抛出错误
        if (!(error_reporting() & $code)) {
            return;
        }

        throw new ErrorException($description, 0, $code, (string) ($file), (int) ($line));
    }

    /**
     * 设置退出句柄.
     *
     * @codeCoverageIgnore
     */
    public function registerShutdownFunction(): void
    {
        if (($error = error_get_last()) && !empty($error['type'])) {
            $this->setExceptionHandler($this->formatErrorException($error));
        }
    }

    /**
     * 响应异常.
     *
     * @param \Throwable $e
     */
    public function setExceptionHandler(Throwable $e): void
    {
        if (!$e instanceof Exception) {
            $e = new ErrorException(
                $e->getMessage(),
                $e->getCode(),
                E_ERROR,
                $e->getFile(),
                $e->getLine(),
                $e->getPrevious()
            );
        }

        try {
            $this->getRuntime()->report($e);
            // @codeCoverageIgnoreStart
        } catch (Exception $e) {
        }
        // @codeCoverageIgnoreEnd

        if ($this->app->console()) {
            $this->renderConsoleResponse($e);
        } else {
            $this->renderHttpResponse($e);
        }
    }

    /**
     * 渲染命令行异常并输出.
     *
     * @param \Exception $e
     */
    protected function renderConsoleResponse(Exception $e): void
    {
        $this
            ->getRuntime()
            ->renderForConsole(new ConsoleOutput(), $e);
    }

    /**
     * 渲染 HTTP 异常并输出.
     *
     * @param \Exception $e
     */
    protected function renderHttpResponse(Exception $e): void
    {
        $request = $this->app
            ->container()
            ->make('request');

        $this
            ->getRuntime()
            ->render($request, $e)
            ->send();
    }

    /**
     * 格式化致命错误信息.
     *
     * @param array $error
     *
     * @return \ErrorException
     */
    protected function formatErrorException(array $error): ErrorException
    {
        return new ErrorException(
            (string) ($error['message']), (int) ($error['type']), 0, (string) ($error['file']), (int) ($error['line'])
        );
    }

    /**
     * 返回运行处理器.
     *
     * @return \Leevel\Kernel\IRuntime
     */
    protected function getRuntime(): IRuntime
    {
        return $this->app
            ->container()
            ->make(IRuntime::class);
    }
}
