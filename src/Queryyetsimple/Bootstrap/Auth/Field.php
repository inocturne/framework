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

namespace Leevel\Bootstrap\Auth;

use Leevel\Auth;

/**
 * 登录字段设置.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2017.09.09
 *
 * @version 1.0
 */
trait Field
{
    /**
     * 设置字段.
     */
    public function setAuthField()
    {
        Auth::setField($this->authField());
    }

    /**
     * 返回所有字段.
     *
     * @return array
     */
    public function authField()
    {
        return [
            'id'          => $this->authFieldId(),
            'name'        => $this->authFieldName(),
            'nikename'    => $this->authFieldNikename(),
            'random'      => $this->authFieldRandom(),
            'email'       => $this->authFieldEmail(),
            'mobile'      => $this->authFieldMobile(),
            'password'    => $this->authFieldPassword(),
            'register_ip' => $this->authFieldRegisterIp(),
            'login_time'  => $this->authFieldLoginTime(),
            'login_ip'    => $this->authFieldLoginIp(),
            'login_count' => $this->authFieldLoginCount(),
            'status'      => $this->authFieldStatus(),
        ];
    }

    /**
     * 登录 ID 字段.
     *
     * @return string
     */
    public function authFieldId()
    {
        return property_exists($this, 'fieldId') ?
            $this->fieldId :
            'id';
    }

    /**
     * 登录帐号字段.
     *
     * @return string
     */
    public function authFieldName()
    {
        return property_exists($this, 'fieldName') ?
            $this->fieldName :
            'name';
    }

    /**
     * 登录昵称字段.
     *
     * @return string
     */
    public function authFieldNikename()
    {
        return property_exists($this, 'fieldNikename') ?
            $this->fieldNikename :
            'nikename';
    }

    /**
     * 登录随机码字段.
     *
     * @return string
     */
    public function authFieldRandom()
    {
        return property_exists($this, 'fieldRandom') ?
            $this->fieldRandom :
            'random';
    }

    /**
     * 登录邮件字段.
     *
     * @return string
     */
    public function authFieldEmail()
    {
        return property_exists($this, 'fieldEmail') ?
            $this->fieldEmail :
            'email';
    }

    /**
     * 登录手机字段.
     *
     * @return string
     */
    public function authFieldMobile()
    {
        return property_exists($this, 'fieldMobile') ?
            $this->fieldMobile :
            'mobile';
    }

    /**
     * 登录密码字段.
     *
     * @return string
     */
    public function authFieldPassword()
    {
        return property_exists($this, 'fieldPassword') ?
            $this->fieldPassword :
            'password';
    }

    /**
     * 登录注册 IP 字段.
     *
     * @return string
     */
    public function authFieldRegisterIp()
    {
        return property_exists($this, 'fieldRegisterIp') ?
            $this->fieldRegisterIp :
            'register_ip';
    }

    /**
     * 登录密码字段.
     *
     * @return string
     */
    public function authFieldLoginTime()
    {
        return property_exists($this, 'fieldLoginTime') ?
            $this->fieldLoginTime :
            'login_time';
    }

    /**
     * 登录 IP 字段.
     *
     * @return string
     */
    public function authFieldLoginIp()
    {
        return property_exists($this, 'fieldLoginIp') ?
            $this->fieldLoginIp :
            'login_ip';
    }

    /**
     * 登录次数字段.
     *
     * @return string
     */
    public function authFieldLoginCount()
    {
        return property_exists($this, 'fieldLoginCount') ?
            $this->fieldLoginCount :
            'login_count';
    }

    /**
     * 登录状态字段.
     *
     * @return string
     */
    public function authFieldStatus()
    {
        return property_exists($this, 'fieldStatus') ?
            $this->fieldStatus :
            'status';
    }

    /**
     * 是否为 ajax.
     *
     * @return bool
     */
    protected function isAjaxRequest()
    {
        return is_ajax_request();
    }
}
