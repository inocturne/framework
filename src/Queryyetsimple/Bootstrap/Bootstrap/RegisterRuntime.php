<?php declare(strict_types=1);
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

use Exception;
use ErrorException;
use Leevel\Bootstrap\Project;
use Leevel\Bootstrap\Runtime\Runtime;
use Leevel\Bootstrap\Runtime\IRuntime;
use Leevel\Bootstrap\Runtime\FatalErrorException;
use Leevel\Bootstrap\Runtime\FatalThrowableError;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * 注册运行时异常
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2018.04.25
 * @version 1.0
 */
class RegisterRuntime
{

    /**
     * 容器
     *
     * @var \Leevel\Di\Project
     */
    protected $project;

    /**
     * 响应
     * 
     * @param \Leevel\Bootstrap\IProject $project
     * @return void
     */
    public function handle(Project $project)
    {
        $this->project = $project;

        error_reporting(E_ALL);

        set_error_handler([$this, 'setErrorHandle']);
        
        set_exception_handler([$this, 'setExceptionHandler']);

        register_shutdown_function([$this, 'registerShutdownFunction']);

        if ($project->environment() === 'production') {
            ini_set('display_errors', 'Off');
        }
    }

    /**
     * 设置错误句柄
     * 
     * @param integer $code
     * @param string $description
     * @param string $file
     * @param interger $line
     * @param mixed $context
     * @return void
     */
    public function setErrorHandle($code, $description, $file = null, $line = null, $context = null)
    {
        // 根据 error_reporing 等级来确定是否抛出错误
        if (! (error_reporting() & $code)) {
            return;
        }

        throw new ErrorException($description, 0, $code, $file, $line);
    }

    /**
     * 设置退出句柄
     *
     * @return void
     */
    public function registerShutdownFunction()
    {
        if (($error = error_get_last()) && ! empty($error['type'])) {
            $this->setExceptionHandler($this->formatFatalException($error));
        }
    }

    /**
     * 响应异常
     *
     * @param \Throwable $e
     * @return void
     */
    public function setExceptionHandler($e)
    {
        if (! $e instanceof Exception) {
            $e = new FatalThrowableError($e);
        }

        try {
            $this->getRuntime()->report($e);
        } catch (Exception $e) {
        }

        if ($this->project->console()) {
           $this->renderConsoleResponse($e);
        } else {
            $this->renderHttpResponse($e);
        }
    }

    /**
     * 渲染命令行异常并输出
     *
     * @param \Exception $e
     * @return void
     */
    protected function renderConsoleResponse(Exception $e)
    {
        $this->getRuntime()->renderForConsole(new ConsoleOutput, $e);
    }

    /**
     * 渲染 HTTP 异常并输出
     *
     * @param \Exception $e
     * @return void
     */
    protected function renderHttpResponse(Exception $e)
    {
        $this->getRuntime()->render($this->project['request'], $e)->send();
    }

    /**
     * 格式化致命错误信息
     *
     * @param array $error
     * @param int|null $traceOffset
     * @return \Leevel\Bootstrap\Runtime\FatalErrorException
     */
    protected function formatFatalException(array $error, $traceOffset = null)
    {
        return new FatalErrorException(
            $error['message'], $error['type'], 0, $error['file'], $error['line'], $traceOffset
        );
    }

    /**
     * 返回运行处理器
     * 
     * @return \Leevel\Bootstrap\Runtime\IRuntime
     */
    protected function getRuntime() {
        return $this->project->make(IRuntime::class);
    }
}