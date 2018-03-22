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

/**
 * 调试默认配置文件
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2016.11.19
 * @version 1.0
 */
return [

    /**
     * ---------------------------------------------------------------
     * 重定向错误页面
     * ---------------------------------------------------------------
     *
     * 系统遇到错误实现的重定向
     */
    'exception_redirect' => '',

    /**
     * ---------------------------------------------------------------
     * 自定义错误模板
     * ---------------------------------------------------------------
     *
     * 你可以让错误消息更加适应你的应用
     */
    'exception_template' => '',

    /**
     * ---------------------------------------------------------------
     * 默认异常错误消息
     * ---------------------------------------------------------------
     *
     * 使用默认消息避免暴露重要的错误消息给用户
     */
    'exception_default_message' => 'error',

    /**
     * ---------------------------------------------------------------
     * 是否显示具体错误
     * ---------------------------------------------------------------
     *
     * 不显示具体错误消息则会采用 exception_default_message 来填充消息
     */
    'exception_show_message' => true
];
