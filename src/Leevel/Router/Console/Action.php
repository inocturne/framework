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

namespace Leevel\Router\Console;

use Leevel\Console\Argument;
use Leevel\Console\Make;
use Leevel\Console\Option;
use Leevel\Router\IRouter;
use function Leevel\Support\Str\camelize;
use Leevel\Support\Str\camelize;

/**
 * 生成方法器.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2017.05.03
 *
 * @version 1.0
 */
class Action extends Make
{
    /**
     * 命令名字.
     *
     * @var string
     */
    protected $name = 'make:action';

    /**
     * 命令描述.
     *
     * @var string
     */
    protected $description = 'Create a new action.';

    /**
     * 命令帮助.
     *
     * @var string
     */
    protected $help = <<<'EOF'
        The <info>%command.name%</info> command to make action with app namespace:
        
          <info>php %command.full_name% controller name</info>
        
        You can also by using the <comment>--namespace</comment> option:
        
          <info>php %command.full_name% controller name --namespace=common</info>
        EOF;

    /**
     * 响应命令.
     *
     * @param \Leevel\Router\IRouter $router
     */
    public function handle(IRouter $router): void
    {
        // 处理命名空间路径
        $this->parseNamespace();

        // 设置模板路径
        $this->setTemplatePath(__DIR__.'/stub/action');

        $controllerNamespace = $router->getControllerDir();
        $controllerName = ucfirst(camelize($this->argument('controller')));

        $action = ucfirst($this->normalizeAction($this->argument('name')));

        $this->setCustomReplaceKeyValue('controller_dir', $controllerNamespace);

        $this->setCustomReplaceKeyValue('file_name', $controllerName);

        $this->setCustomReplaceKeyValue('controller', $controllerName);

        $this->setCustomReplaceKeyValue('action', $action);

        // 保存路径
        $this->setSaveFilePath(
            $this->getNamespacePath().
            str_replace('\\', '/', $controllerNamespace).'/'.
            $controllerName.'/'.$action.'.php'
        );

        // 设置类型
        $this->setMakeType('action');

        // 执行
        $this->create();
    }

    /**
     * 格式化方法名.
     *
     * @param string $action
     *
     * @return string
     */
    protected function normalizeAction(string $action): string
    {
        if (false !== strpos($action, '-')) {
            $action = str_replace('-', '_', $action);
        }

        if (false !== strpos($action, '_')) {
            $action = '_'.str_replace('_', ' ', $action);
            $action = ltrim(str_replace(' ', '', ucwords($action)), '_');
        }

        return $action;
    }

    /**
     * 命令参数.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            [
                'controller',
                Argument::OPTIONAL,
                'This is the parent controller name.',
            ],
            [
                'name',
                Argument::OPTIONAL,
                'This is the action name.',
            ],
        ];
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
                'namespace',
                null,
                Option::VALUE_OPTIONAL,
                'Apps namespace registered to system,default namespace is these (Common,App,Admin)',
                'app',
            ],
        ];
    }
}

// import fn.
class_exists(camelize::class);
