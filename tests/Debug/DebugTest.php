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

namespace Tests\Debug;

use Error;
use Exception;
use Leevel\Debug\Debug;
use Leevel\Event\Dispatch;
use Leevel\Event\IDispatch;
use Leevel\Http\JsonResponse;
use Leevel\Http\Request;
use Leevel\Http\Response;
use Leevel\Leevel\Project as Projects;
use Leevel\Log\File as LogFile;
use Leevel\Log\ILog;
use Leevel\Log\Log;
use Leevel\Option\IOption;
use Leevel\Option\Option;
use Leevel\Session\File as SessionFile;
use Leevel\Session\ISession;
use Leevel\Session\Session;
use Tests\TestCase;

/**
 * debug test.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2018.09.24
 *
 * @version 1.0
 *
 * @api(
 *     title="Debug",
 *     path="component/debug",
 *     description="添加一个组件调试。",
 * )
 */
class DebugTest extends TestCase
{
    public function testBaseUse()
    {
        $debug = $this->createDebug();

        $this->createProject();

        $this->assertFalse($debug->isBootstrap());

        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        // twice same with once
        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        $request = new Request();
        $response = new Response();

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertContains('<link rel="stylesheet" type="text/css" href="/debugbar/vendor/font-awesome/css/font-awesome.min.css">', $content);

        $this->assertContains('<link rel="stylesheet" type="text/css" href="/debugbar/debugbar.css">', $content);

        $this->assertContains('var phpdebugbar = new PhpDebugBar.DebugBar()', $content);

        $this->assertContains("console.log( '%cThe PHP Framework For Code Poem As Free As Wind %c(http://www.queryphp.com)', 'font-weight: bold;color: #06359a;', 'color: #02d629;' );", $content);

        $this->assertContains('Starts from this moment with QueryPHP.', $content);
    }

    /**
     * @api(
     *     title="JSON 关联数组调试",
     *     description="关联数组结构会在尾部追加一个选项 `:trace` 用于调试。
     *
     * _**返回结构**_
     *
     * ``` php
     * $response = [\"foo\" => \"bar\", \":trace\" => []];
     * ```
     *
     * 关联数组在尾部追加一个选项作为调试信息，这与非关联数组有所不同。
     * ",
     *     note="",
     * )
     */
    public function testJson()
    {
        $debug = $this->createDebug();

        $this->assertFalse($debug->isBootstrap());

        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        $request = new Request();
        $response = new JsonResponse(['foo' => 'bar']);

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertContains('{"foo":"bar",":trace":', $content);

        $this->assertContains('"php":{"version":', $content);

        $this->assertContains('Starts from this moment with QueryPHP.', $content);
    }

    /**
     * @api(
     *     title="JSON 非关联数组调试",
     *     description="非关联数组结构会在尾部追加一个 `:trace` 用于调试。
     *
     * _**返回结构**_
     *
     * ``` php
     * $response = [\"foo\", \"bar\", [\":trace\" => []]];
     * ```
     *
     * 非关联数组在尾部追加一个调试信息，将不会破坏返回接口的 JSON 结构。
     * ",
     *     note="",
     * )
     */
    public function testJsonForNotAssociativeArray()
    {
        $debug = $this->createDebug();

        $this->assertFalse($debug->isBootstrap());

        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        $request = new Request();
        $response = new JsonResponse(['foo', 'bar']);

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertContains('"foo","bar",{":trace":{', $content);

        $this->assertContains('"php":{"version":', $content);

        $this->assertContains('Starts from this moment with QueryPHP.', $content);
    }

    /**
     * @api(
     *     title="关闭调试",
     *     description="",
     *     note="",
     * )
     */
    public function testDisable()
    {
        $debug = $this->createDebug();

        $this->assertFalse($debug->isBootstrap());

        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        $request = new Request();
        $response = new JsonResponse(['foo' => 'bar']);

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertContains('{"foo":"bar",":trace":', $content);

        $this->assertContains('"php":{"version":', $content);

        $this->assertContains('Starts from this moment with QueryPHP.', $content);

        $debug->disable();

        $response2 = new JsonResponse(['foo' => 'bar']);

        $debug->handle($request, $response2);

        $content = $response2->getContent();

        $this->assertNotContains('{"foo":"bar",":trace":', $content);

        $this->assertNotContains('"php":{"version":', $content);

        $this->assertNotContains('Starts from this moment with QueryPHP.', $content);
    }

    /**
     * @api(
     *     title="启用调试",
     *     description="",
     *     note="",
     * )
     */
    public function testEnable()
    {
        $debug = $this->createDebug();

        $this->assertFalse($debug->isBootstrap());

        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        $request = new Request();
        $response = new JsonResponse(['foo' => 'bar']);

        $debug->disable();

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertNotContains('{"foo":"bar",":trace":', $content);

        $this->assertNotContains('"php":{"version":', $content);

        $this->assertNotContains('Starts from this moment with QueryPHP.', $content);

        $this->assertTrue($debug->isBootstrap());

        $debug->enable();

        $this->assertTrue($debug->isBootstrap());

        $response2 = new JsonResponse(['foo' => 'bar']);

        $debug->handle($request, $response2);

        $content = $response2->getContent();

        $this->assertContains('{"foo":"bar",":trace":', $content);

        $this->assertContains('"php":{"version":', $content);

        $this->assertContains('Starts from this moment with QueryPHP.', $content);
    }

    /**
     * @api(
     *     title="启用调试但是未初始化",
     *     description="",
     *     note="",
     * )
     */
    public function testEnableWithoutBootstrap()
    {
        $debug = $this->createDebug();

        $request = new Request();
        $response = new JsonResponse(['foo' => 'bar']);

        $debug->disable();

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertNotContains('{"foo":"bar",":trace":', $content);

        $this->assertNotContains('"php":{"version":', $content);

        $this->assertNotContains('Starts from this moment with QueryPHP.', $content);

        $this->assertFalse($debug->isBootstrap());

        $debug->enable();

        $this->assertTrue($debug->isBootstrap());

        $response2 = new JsonResponse(['foo' => 'bar']);

        $debug->handle($request, $response2);

        $content = $response2->getContent();

        $this->assertContains('{"foo":"bar",":trace":', $content);

        $this->assertContains('"php":{"version":', $content);

        $this->assertContains('Starts from this moment with QueryPHP.', $content);
    }

    public function testEnableTwiceSameWithOne()
    {
        $debug = $this->createDebug();

        $this->assertFalse($debug->isBootstrap());

        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        $request = new Request();
        $response = new JsonResponse(['foo' => 'bar']);

        $debug->disable();

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertNotContains('{"foo":"bar",":trace":', $content);

        $this->assertNotContains('"php":{"version":', $content);

        $this->assertNotContains('Starts from this moment with QueryPHP.', $content);

        $this->assertTrue($debug->isBootstrap());

        $debug->enable();

        $this->assertTrue($debug->isBootstrap());

        $response2 = new JsonResponse(['foo' => 'bar']);

        $debug->handle($request, $response2);

        $content = $response2->getContent();

        $this->assertContains('{"foo":"bar",":trace":', $content);

        $this->assertContains('"php":{"version":', $content);

        $this->assertContains('Starts from this moment with QueryPHP.', $content);
    }

    /**
     * @dataProvider getMessageLevelsData
     *
     * @param string $level
     *
     * @api(
     *     title="调试消息等级",
     *     description="
     * _**支持的消息类型**_
     *
     * ``` php
     * ".\Leevel\Leevel\Utils\Doc::getMethodBody(\Tests\Debug\DebugTest::class, 'getMessageLevelsData')."
     * ```
     *
     * 系统支持多种消息类型，可以参考这个进行调试。
     * ",
     *     note="",
     * )
     */
    public function testMessageLevelsData(string $level)
    {
        $debug = $this->createDebug();

        $this->assertFalse($debug->isBootstrap());

        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        $request = new Request();
        $response = new JsonResponse(['foo' => 'bar']);

        $debug->{$level}('hello', 'world');

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertContains('{"foo":"bar",":trace":', $content);

        $this->assertContains('"php":{"version":', $content);

        $this->assertContains('Starts from this moment with QueryPHP.', $content);

        $this->assertContains('{"message":"hello","message_html":null,"is_string":true,"label":"'.$level.'",', $content);
        $this->assertContains('{"message":"world","message_html":null,"is_string":true,"label":"'.$level.'",', $content);
    }

    public function getMessageLevelsData()
    {
        return [
            ['emergency'], ['alert'], ['critical'],
            ['error'], ['warning'], ['notice'],
            ['info'], ['debug'], ['log'],
        ];
    }

    /**
     * @api(
     *     title="调试 Session",
     *     description="",
     *     note="",
     * )
     */
    public function testWithSession()
    {
        $debug = $this->createDebug();

        $this->assertFalse($debug->isBootstrap());

        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        $request = new Request();
        $response = new JsonResponse(['foo' => 'bar']);

        $session = $debug->getProject()->make('session');

        $session->set('test_session', 'test_value');

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertContains('"session":{"test_session":"test_value"},', $content);
    }

    /**
     * @api(
     *     title="调试 Log",
     *     description="",
     *     note="",
     * )
     */
    public function testWithLog()
    {
        $debug = $this->createDebugWithLog();

        $project = $debug->getProject();

        $this->assertFalse($debug->isBootstrap());

        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        $request = new Request();
        $response = new JsonResponse(['foo' => 'bar']);

        $log = $project->make('log');

        $log->info('test_log', ['exends' => 'bar']);
        $log->debug('test_log_debug');

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertContains('"logs":{"count":2,', $content);

        $this->assertContains('test_log info: {\"exends\":\"bar\"}', $content);

        $this->assertContains('test_log_debug debug: []', $content);
    }

    /**
     * @api(
     *     title="调试时间",
     *     description="",
     *     note="",
     * )
     */
    public function testTime()
    {
        $debug = $this->createDebug();

        $this->assertFalse($debug->isBootstrap());

        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        $request = new Request();
        $response = new JsonResponse(['foo' => 'bar']);

        $debug->time('time_test');

        $debug->end('time_test');

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertContains('"time":{"start"', $content);

        $this->assertContains('"measures":[{"label":"time_test","start":', $content);
    }

    /**
     * @api(
     *     title="调试带有标签的时间",
     *     description="",
     *     note="",
     * )
     */
    public function testTimeWithLabel()
    {
        $debug = $this->createDebug();

        $this->assertFalse($debug->isBootstrap());

        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        $request = new Request();
        $response = new JsonResponse(['foo' => 'bar']);

        $debug->time('time_test', 'time_label');

        $debug->end('time_test');

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertContains('"time":{"start"', $content);

        $this->assertContains('"measures":[{"label":"time_label","start":', $content);
    }

    public function testEndWithNoStartDoNothing()
    {
        $debug = $this->createDebug();

        $this->assertFalse($debug->isBootstrap());

        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        $request = new Request();
        $response = new JsonResponse(['foo' => 'bar']);

        $debug->end('time_without_start');

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertContains('"time":{"start"', $content);

        $this->assertContains('"measures":[]', $content);
    }

    public function testAddTime()
    {
        $debug = $this->createDebug();

        $this->assertFalse($debug->isBootstrap());

        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        $request = new Request();
        $response = new JsonResponse(['foo' => 'bar']);

        $debug->addTime('time_test', 1, 5);

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertContains('"time":{"start"', $content);

        $this->assertContains('"measures":[{"label":"time_test","start":1', $content);

        $this->assertContains('"end":5,', $content);

        $this->assertContains('"relative_end":5,', $content);

        $this->assertContains('"duration":4,', $content);

        $this->assertContains('"duration_str":"4s",', $content);
    }

    public function testClosureTime()
    {
        $debug = $this->createDebug();

        $this->assertFalse($debug->isBootstrap());

        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        $request = new Request();
        $response = new JsonResponse(['foo' => 'bar']);

        $debug->closureTime('time_test', function () {
        });

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertContains('"time":{"start"', $content);

        $this->assertContains('"measures":[{"label":"time_test","start":', $content);
    }

    public function testException()
    {
        $debug = $this->createDebug();

        $this->assertFalse($debug->isBootstrap());

        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        $request = new Request();
        $response = new JsonResponse(['foo' => 'bar']);

        $debug->exception(new Exception('test_exception'));

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertContains('"exceptions":{"count":1,"exceptions":[', $content);

        $this->assertContains('"type":"Exception",', $content);

        $this->assertContains('"message":"test_exception",', $content);

        $this->assertContains('"code":0,', $content);

        $this->assertContains('$response = new JsonResponse([\'foo\' => \'bar\']);', $content);

        $this->assertContains('$debug->exception(new Exception(\'test_exception\'));', $content);
    }

    public function testExceptionWithError()
    {
        $debug = $this->createDebug();

        $this->assertFalse($debug->isBootstrap());

        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        $request = new Request();
        $response = new JsonResponse(['foo' => 'bar']);

        $debug->exception(new Error('test_error'));

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertContains('"exceptions":{"count":1,"exceptions":[', $content);

        $this->assertContains('"type":"Error",', $content);

        $this->assertContains('"message":"test_error",', $content);

        $this->assertContains('"code":0,', $content);

        $this->assertContains('$response = new JsonResponse([\'foo\' => \'bar\']);', $content);

        $this->assertContains('$debug->exception(new Error(\'test_error\'));', $content);
    }

    public function testSetOptionWithoutJson()
    {
        $debug = $this->createDebug();

        $this->assertFalse($debug->isBootstrap());

        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        $request = new Request();
        $response = new JsonResponse(['foo' => 'bar']);

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertContains('{"foo":"bar",":trace":', $content);

        $this->assertContains('"php":{"version":', $content);

        $this->assertContains('Starts from this moment with QueryPHP.', $content);

        $debug->setOption('json', false);

        $response2 = new JsonResponse(['foo' => 'bar']);

        $debug->handle($request, $response2);

        $content = $response2->getContent();

        $this->assertNotContains('{"foo":"bar",":trace":', $content);

        $this->assertNotContains('"php":{"version":', $content);

        $this->assertNotContains('Starts from this moment with QueryPHP.', $content);
    }

    public function testSetOptionWithoutJavascriptAndConsole()
    {
        $debug = $this->createDebug();

        $this->assertFalse($debug->isBootstrap());

        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        // twice same with once
        $debug->bootstrap();

        $this->assertTrue($debug->isBootstrap());

        $request = new Request();
        $response = new Response();

        $debug->handle($request, $response);

        $content = $response->getContent();

        $this->assertContains('<link rel="stylesheet" type="text/css" href="/debugbar/vendor/font-awesome/css/font-awesome.min.css">', $content);

        $this->assertContains('<link rel="stylesheet" type="text/css" href="/debugbar/debugbar.css">', $content);

        $this->assertContains('var phpdebugbar = new PhpDebugBar.DebugBar()', $content);

        $this->assertContains("console.log( '%cThe PHP Framework For Code Poem As Free As Wind %c(http://www.queryphp.com)', 'font-weight: bold;color: #06359a;', 'color: #02d629;' );", $content);

        $this->assertContains('Starts from this moment with QueryPHP.', $content);

        $debug->setOption('javascript', false);
        $debug->setOption('console', false);

        $response2 = new Response();

        $debug->handle($request, $response2);

        $content = $response2->getContent();

        $this->assertNotContains('<link rel="stylesheet" type="text/css" href="/debugbar/vendor/font-awesome/css/font-awesome.min.css">', $content);

        $this->assertNotContains('<link rel="stylesheet" type="text/css" href="/debugbar/debugbar.css">', $content);

        $this->assertNotContains('var phpdebugbar = new PhpDebugBar.DebugBar()', $content);

        $this->assertNotContains("console.log( '%cThe PHP Framework For Code Poem As Free As Wind %c(http://www.queryphp.com)', 'font-weight: bold;color: #06359a;', 'color: #02d629;' );", $content);

        $this->assertNotContains('Starts from this moment with QueryPHP.', $content);

        $this->assertSame('', $content);
    }

    protected function createDebugWithLog()
    {
        return new Debug($this->createProjectWithLog());
    }

    protected function createProjectWithLog(): Project
    {
        $project = new Project();

        $project->instance('session', $this->createSession());

        $project->instance('option', $this->createOption());

        $eventDispatch = new Dispatch($project);
        $project->singleton(IDispatch::class, $eventDispatch);

        $project->instance('log', $this->createLog($eventDispatch));

        return $project;
    }

    protected function createDebug(): Debug
    {
        return new Debug($this->createProject());
    }

    protected function createProject(): Project
    {
        $project = new Project();

        $project->instance('session', $this->createSession());

        $project->instance('log', $this->createLog());

        $project->instance('option', $this->createOption());

        $eventDispatch = $this->createMock(IDispatch::class);

        $eventDispatch->method('handle')->willReturn(null);
        $this->assertNull($eventDispatch->handle('event'));

        $project->singleton(IDispatch::class, $eventDispatch);

        return $project;
    }

    protected function createSession(): ISession
    {
        $file = new SessionFile([
            'path' => __DIR__.'/cacheFile',
        ]);

        $session = new Session($file);

        $this->assertInstanceof(ISession::class, $session);

        return $session;
    }

    protected function createLog(IDispatch $dispatch = null): ILog
    {
        $file = new LogFile([
            'path' => __DIR__.'/cacheLog',
        ]);

        $log = new Log($file, [], $dispatch);

        $this->assertInstanceof(ILog::class, $log);

        return $log;
    }

    protected function createOption(): IOption
    {
        $data = [
            'app' => [
                'environment'       => 'environment',
            ],
            'debug' => [
                'json'       => true,
                'console'    => true,
                'javascript' => true,
            ],
        ];

        $option = new Option($data);

        $this->assertInstanceof(IOption::class, $option);

        return $option;
    }
}

class Project extends Projects
{
    protected function registerBaseProvider(): void
    {
    }
}
