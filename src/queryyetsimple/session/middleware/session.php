<?php
// [$QueryPHP] The PHP Framework For Code Poem As Free As Wind. <Query Yet Simple>
// ©2010-2017 http://queryphp.com All rights reserved.
namespace queryyetsimple\session\middleware;

<<<queryphp
##########################################################
#   ____                          ______  _   _ ______   #
#  /     \       ___  _ __  _   _ | ___ \| | | || ___ \  #
# |   (  ||(_)| / _ \| '__|| | | || |_/ /| |_| || |_/ /  #
#  \____/ |___||  __/| |   | |_| ||  __/ |  _  ||  __/   #
#       \__   | \___ |_|    \__  || |    | | | || |      #
#     Query Yet Simple      __/  |\_|    |_| |_|\_|      #
#                          |___ /  Since 2010.10.03      #
##########################################################
queryphp;

use Closure;
use queryyetsimple\http\request;
use queryyetsimple\http\response;
use queryyetsimple\session\session as manager;

/**
 * session 中间件
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2017.11.14
 * @version 1.0
 */
class session {
    
    /**
     * session 管理
     *
     * @var \queryyetsimple\session\session
     */
    protected $objManager;
    
    /**
     * 构造函数
     *
     * @param \queryyetsimple\throttler\ithrottler $objManager            
     * @return void
     */
    public function __construct(manager $objManager) {
        $this->objManager = $objManager;
    }
    
    /**
     * 请求
     *
     * @param \Closure $calNext            
     * @param \queryyetsimple\http\request $objRequest            
     * @return mixed
     */
    public function handle(Closure $calNext, request $objRequest) {
        $this->startSession ();
        return $calNext ( $objRequest );
    }
    
    /**
     * 响应
     *
     * @param \queryyetsimple\http\request $objRequest            
     * @param \queryyetsimple\http\response $mixResponse            
     * @return mixed
     */
    public function terminate(Closure $calNext, request $objRequest, response $objResponse) {
        $this->unregisterFlash ();
        $this->setPrevUrl ( $objRequest );
        return $calNext ( $objRequest, $objResponse );
    }
    
    /**
     * 启动 session
     *
     * @return void
     */
    protected function startSession() {
        $this->objManager->start ();
    }
    
    /**
     * 清理闪存
     *
     * @return void
     */
    protected function unregisterFlash() {
        $this->objManager->unregisterFlash ();
    }
    
    /**
     * 保存当期请求 URL
     *
     * @param \queryyetsimple\http\request $objRequest            
     * @return void
     */
    protected function setPrevUrl(request $objRequest) {
        $this->objManager->setPrevUrl ( $objRequest->url () );
    }
}