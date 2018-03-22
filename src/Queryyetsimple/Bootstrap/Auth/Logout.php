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
namespace Queryyetsimple\Bootstrap\auth;

use Queryyetsimple\{
    auth,
    response
};

/**
 * 退出登录
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2017.09.09
 * @version 1.0
 */
trait logout
{
    use field;

    /**
     * 登录退出
     *
     * @return \Queryyetsimple\Http\Response
     */
    public function logout()
    {
        return $this->displayLoginout();
    }

    /**
     * 登录退出
     *
     * @return \Queryyetsimple\Http\Response|array
     */
    public function displayLoginout()
    {
        auth::logout();

        if ($this->isAjaxRequest()) {
            return [
                'message' => $this->getLogoutMessage()
            ];
        }

        return response::redirect($this->getLogoutRedirect())->with('login_out', $this->getLogoutMessage());
    }

    /**
     * 获取退出登录消息
     *
     * @return string
     */
    protected function getLogoutMessage()
    {
        return __('退出成功');
    }

    /**
     * 获取退出转向地址
     *
     * @return string
     */
    protected function getLogoutRedirect()
    {
        return property_exists($this, 'strLogoutRedirect') ? $this->strLogoutRedirect : 'auth/index';
    }
}
