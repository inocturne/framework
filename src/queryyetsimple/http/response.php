<?php
// [$QueryPHP] The PHP Framework For Code Poem As Free As Wind. <Query Yet Simple>
// ©2010-2017 http://queryphp.com All rights reserved.
namespace queryyetsimple\http;

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

use queryyetsimple\xml\xml;
use InvalidArgumentException;
use queryyetsimple\flow\control;
use queryyetsimple\assert\assert;
use queryyetsimple\router\router;
use queryyetsimple\classs\option;
use queryyetsimple\filesystem\file;
use queryyetsimple\classs\infinity;
use queryyetsimple\mvc\interfaces\view;
use queryyetsimple\cookie\interfaces\cookie;
use queryyetsimple\session\interfaces\session;

/**
 * 响应请求
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2017.04.18
 * @version 1.0
 */
class response {
    
    use control;
    use option{
        option as infinityOption;
        options as infinityOptions;
    }
    use infinity {
        __call as infinityCall;
    }
    
    /**
     * view
     *
     * @var \queryyetsimple\mvc\interfaces\view
     */
    protected $objView;
    
    /**
     * session 处理
     *
     * @var \queryyetsimple\session\interfaces\session
     */
    protected $objSession;
    
    /**
     * cookie 处理
     *
     * @var \queryyetsimple\cookie\interfaces\cookie
     */
    protected $objCookie;
    
    /**
     * router
     *
     * @var \queryyetsimple\router\router
     */
    protected $objRouter;
    
    /**
     * 响应数据
     *
     * @var mixed
     */
    protected $mixData;
    
    /**
     * 设置内容
     *
     * @var string
     */
    protected $strContent;
    
    /**
     * 是否分析过内容
     *
     * @var boolean
     */
    protected $booParseContent = false;
    
    /**
     * 响应状态
     *
     * @var int
     */
    protected $intCode = 200;
    
    /**
     * 消息内容
     *
     * @var int
     */
    protected $strMessage = '';
    
    /**
     * 响应头
     *
     * @var array
     */
    protected $arrHeader = [ ];
    
    /**
     * 响应类型
     *
     * @var string
     */
    protected $strContentType = 'text/html';
    
    /**
     * 字符编码
     *
     * @var string
     */
    protected $strCharset = 'utf-8';
    
    /**
     * 响应类型
     *
     * @var string
     */
    protected $strResponseType = 'default';
    
    /**
     * json 配置
     *
     * @var array
     */
    protected static $arrJsonOption = [ 
            'json_callback' => '',
            'json_options' => JSON_UNESCAPED_UNICODE 
    ];
    
    /**
     * 配置
     *
     * @var array
     */
    protected $arrOption = [ 
            'action_fail' => 'public+fail',
            'action_success' => 'public+success',
            'default_response' => 'default' 
    ];
    
    /**
     * 构造函数
     *
     * @param \queryyetsimple\router\router $objRouter            
     * @param \queryyetsimple\mvc\interfaces\view $objView            
     * @param \queryyetsimple\session\interfaces\session $objSession            
     * @param \queryyetsimple\cookie\interfaces\cookie $objCookie            
     * @param array $arrOption            
     * @return void
     */
    public function __construct(router $objRouter, view $objView, session $objSession, cookie $objCookie, array $arrOption = []) {
        $this->objRouter = $objRouter;
        $this->objView = $objView;
        $this->objSession = $objSession;
        $this->objCookie = $objCookie;
        $this->options ( $arrOption );
    }
    
    /**
     * 创建一个响应
     *
     * @param mixed $mixData            
     * @param int $intCode            
     * @param string $strMessage            
     * @param array $arrHeader            
     * @param array $arrOption            
     * @return $this
     */
    public function make($mixData = '', $intCode = 200, $strMessage = '', array $arrHeader = [], $arrOption = []) {
        return $this->data ( $mixData )->code ( intval ( $intCode ) )->message ( $strMessage )->headers ( $arrHeader )->options ( $arrOption );
    }
    
    /**
     * 拦截一些别名和快捷方式
     *
     * @param 方法名 $sMethod            
     * @param 参数 $arrArgs            
     * @return boolean
     */
    public function __call($sMethod, $arrArgs) {
        if ($this->placeholderFlowControl ( $sMethod )) {
            return $this;
        }
        
        // 调用 trait __call 实现扩展方法
        $mixData = $this->infinityCall ( $sMethod, $arrArgs );
        if ($mixData instanceof response) {
            return $mixData;
        } else {
            return $this->data ( $mixData );
        }
    }
    
    /**
     * 输出内容
     *
     * @return void
     */
    public function output() {
        // 组装编码
        $this->contentTypeAndCharset ( $this->getContentType (), $this->getrCharset () );
        
        // 发送头部 header
        if (! headers_sent () && ! empty ( $this->arrHeader )) {
            http_response_code ( $this->intCode );
            foreach ( $this->arrHeader as $strName => $strValue ) {
                header ( $strName . ':' . $strValue );
            }
        }
        
        // 输出内容
        echo $this->getContent ();
        
        // 提高响应速速
        if (function_exists ( 'fastcgi_finish_request' )) {
            fastcgi_finish_request ();
        }
    }
    
    /**
     * 设置头部参数
     *
     * @param string $strName            
     * @param string $strValue            
     * @return $this
     */
    public function header($strName, $strValue) {
        if ($this->checkFlowControl ())
            return $this;
        $this->arrHeader [$strName] = $strValue;
        return $this;
    }
    
    /**
     * 批量设置头部参数
     *
     * @param array $arrHeader            
     * @return $this
     */
    public function headers($arrHeader) {
        if ($this->checkFlowControl ())
            return $this;
        $this->arrHeader = array_merge ( $this->arrHeader, $arrHeader );
        return $this;
    }
    
    /**
     * 返回头部参数
     *
     * @param string $strHeaderName            
     * @return mixed
     */
    public function getHeader($strHeaderName = null) {
        if (is_null ( $strHeaderName )) {
            return $this->arrHeader;
        } else {
            return isset ( $this->arrHeader [$strHeaderName] ) ? $this->arrHeader [$strHeaderName] : null;
        }
    }
    
    /**
     * 修改单个配置
     *
     * @param string $strName            
     * @param mixed $mixValue            
     * @return $this
     */
    public function option($strName, $mixValue) {
        if ($this->checkFlowControl ())
            return $this;
        return $this->infinityOption ( $strName, $mixValue );
    }
    
    /**
     * 修改多个配置
     *
     * @param string $strName            
     * @param mixed $mixValue            
     * @return $this
     */
    public function options($arrOption) {
        if ($this->checkFlowControl ())
            return $this;
        return $this->infinityOptions ( $arrOption );
    }
    
    /**
     * 设置响应 cookie
     *
     * @param string $sName            
     * @param mixed $mixValue            
     * @param array $arrOption            
     * @return $this
     */
    public function withCookie($sName, $mixValue = '', array $arrOption = []) {
        if ($this->checkFlowControl ())
            return $this;
        $this->objCookie->set ( $sName, $mixValue, $arrOption );
        return $this;
    }
    
    /**
     * 批量设置响应 cookie
     *
     * @param array $arrCookie            
     * @param array $arrOption            
     * @return $this
     */
    public function withCookies(array $arrCookie, array $arrOption = []) {
        if ($this->checkFlowControl ())
            return $this;
        foreach ( $arrCookie as $sName => $mixValue )
            $this->objCookie->set ( $sName, $mixValue, $arrOption );
        return $this;
    }
    
    /**
     * 闪存错误信息
     *
     * @param array $arrErrors            
     * @return $this
     */
    public function withErrors(array $arrErrors) {
        if ($this->checkFlowControl ())
            return $this;
        $this->objSession->flash ( 'errors', array_merge ( $this->objSession->getFlash ( 'errors', [ ] ), $arrErrors ) );
        return $this;
    }
    
    /**
     * 闪存输入信息
     *
     * @param array $arrInputs            
     * @return $this
     */
    public function withInputs(array $arrInputs) {
        if ($this->checkFlowControl ())
            return $this;
        $this->objSession->flash ( 'inputs', array_merge ( $this->objSession->getFlash ( 'inputs', [ ] ), $arrInputs ) );
        return $this;
    }
    
    /**
     * 设置原始数据
     *
     * @param mixed $mixData            
     * @return $this
     */
    public function data($mixData) {
        if ($this->checkFlowControl ())
            return $this;
        $this->mixData = $mixData;
        return $this;
    }
    
    /**
     * 返回原始数据
     *
     * @return $this
     */
    public function getData() {
        return $this->mixData;
    }
    
    /**
     * 响应状态
     *
     * @param int $intCode            
     * @return $this
     */
    public function code($intCode) {
        if ($this->checkFlowControl ())
            return $this;
        $this->intCode = intval ( $intCode );
        return $this;
    }
    
    /**
     * 返回响应状态
     *
     * @return number
     */
    public function getCode() {
        return $this->intCode;
    }
    
    /**
     * 消息内容
     *
     * @param string $strMessage            
     * @return $this
     */
    public function message($strMessage) {
        if ($this->checkFlowControl ())
            return $this;
        $this->strMessage = $strMessage;
        return $this;
    }
    
    /**
     * 返回消息内容
     *
     * @return string
     */
    public function getMessage() {
        return $this->strMessage;
    }
    
    /**
     * contentType
     *
     * @param string $strContentType            
     * @return $this
     */
    public function contentType($strContentType) {
        if ($this->checkFlowControl ())
            return $this;
        $this->strContentType = $strContentType;
        return $this;
    }
    
    /**
     * 返回 contentType
     *
     * @return string
     */
    public function getContentType() {
        return $this->strContentType;
    }
    
    /**
     * 编码设置
     *
     * @param string $strCharset            
     * @return $this
     */
    public function charset($strCharset) {
        if ($this->checkFlowControl ())
            return $this;
        $this->strCharset = $strCharset;
        return $this;
    }
    
    /**
     * 获取编码
     *
     * @return string
     */
    public function getrCharset() {
        return $this->strCharset;
    }
    
    /**
     * 设置内容
     *
     * @param string $strContent            
     * @return $this
     */
    public function content($strContent) {
        if ($this->checkFlowControl ())
            return $this;
        $this->strContent = $strContent;
        $this->booParseContent = true;
        return $this;
    }
    
    /**
     * 解析并且返回内容
     *
     * @return string
     */
    public function getContent() {
        if (! $this->booParseContent) {
            $mixContent = $this->getData ();
            switch ($this->getResponseType ()) {
                case 'json' :
                    if ($this->isApi ())
                        $mixContent = $this->api ( $mixContent, null, null, true );
                    else
                        $mixContent = json_encode ( $mixContent, $this->getJsonOption ()['json_options'] );
                    if ($this->getJsonOption ()['json_callback']) {
                        $mixContent = $this->getJsonOption ()['json_callback'] . '(' . $mixContent . ');';
                    }
                    break;
                case 'xml' :
                    $mixContent = xml::serialize ( $mixContent );
                    break;
                case 'file' :
                    ob_end_clean ();
                    $resFp = fopen ( $this->getOption ( 'file_name' ), 'rb' );
                    fpassthru ( $resFp );
                    fclose ( $resFp );
                    break;
                case 'redirect' :
                    $this->objRouter->redirect ( $this->getOption ( 'redirect_url' ), $this->getOption ( 'option' ) );
                    break;
                case 'view' :
                    if ($this->isApi ())
                        $mixContent = $this->api ( $this->objView->getAssign (), null, null, true );
                    else
                        $mixContent = $this->objView->display ( $this->getOption ( 'file' ), $this->getOption ( 'option' ) );
                    break;
                default :
                    if (is_callable ( $mixContent )) {
                        $mixTemp = call_user_func_array ( $mixContent, [ ] );
                        if ($mixTemp !== null) {
                            $mixContent = $mixTemp;
                        }
                        unset ( $mixTemp );
                    } elseif (is_array ( $mixContent )) {
                        if (! $this->isApi ()) {
                            $mixContent = json_encode ( $mixContent, $this->getJsonOption ()['json_options'] );
                        }
                    }
                    $mixContent = $this->varString ( $mixContent );
                    if ($this->isApi ()) {
                        $mixContent = $this->api ( $mixContent, null, null, true );
                    }
                    break;
            }
            $this->content ( $mixContent );
            unset ( $mixContent );
        }
        return $this->strContent;
    }
    
    /**
     * api 接口形式
     *
     * @param mixed $mixContent            
     * @param int|null $intCode            
     * @param string|null $strMessage            
     * @param boolean $booReturn            
     * @return json|$this mixed
     */
    public function api($mixContent, $intCode = null, $strMessage = null, $booReturn = false) {
        $mixContent = $this->varString ( $mixContent );
        
        if (! is_null ( $intCode ))
            $this->code ( intval ( $intCode ) );
        
        if (! is_null ( $strMessage ))
            $this->message ( $strMessage );
        
        $strReturn = json_encode ( [
                // 反码状态
                'code' => $this->getCode (),
                
                // 描述信息
                'message' => $this->getMessage (),
                
                // 响应时间
                'time' => time (),
                
                // 数据
                'data' => is_array ( $mixContent ) ? $mixContent : [ 
                        'content' => $mixContent 
                ] 
        ], $this->getJsonOption ()['json_options'] );
        
        if ($booReturn === true) {
            return $strReturn;
        } else {
            $this->content ( $strReturn );
            unset ( $strReturn );
            return $this;
        }
    }
    
    /**
     * 判断是否 api 模式
     *
     * @return boolean
     */
    public function isApi() {
        return $this->getOption ( 'default_response' ) == 'api';
    }
    
    /**
     * 返回 JSON 配置
     *
     * @return array
     */
    public function getJsonOption() {
        return array_merge ( static::$arrJsonOption, $this->getOptions () );
    }
    
    /**
     * 设置相应类型
     *
     * @param string $strResponseType            
     * @return $this
     */
    public function responseType($strResponseType) {
        if ($this->checkFlowControl ())
            return $this;
        $this->strResponseType = $strResponseType;
        return $this;
    }
    
    /**
     * 返回相应类型
     *
     * @return string
     */
    public function getResponseType() {
        return $this->strResponseType;
    }
    
    /**
     * jsonp
     *
     * @param array $arrData            
     * @param int $intOptions            
     * @param string $strCharset            
     * @return $this
     */
    public function json($arrData = null, $intOptions = JSON_UNESCAPED_UNICODE, $strCharset = 'utf-8') {
        if ($this->checkFlowControl ())
            return $this;
        if (is_array ( $arrData )) {
            $this->data ( $arrData );
        }
        $this->responseType ( 'json' )->contentType ( 'application/json' )->charset ( $strCharset )->option ( 'json_options', $intOptions );
        return $this;
    }
    
    /**
     * json callback
     *
     * @param string $strJsonCallback            
     * @return $this
     */
    public function jsonCallback($strJsonCallback) {
        if ($this->checkFlowControl ())
            return $this;
        return $this->option ( 'json_callback', $strJsonCallback );
    }
    
    /**
     * jsonp
     *
     * @param string $strJsonCallback            
     * @param array $arrData            
     * @param int $intOptions            
     * @param string $strCharset            
     * @return $this
     */
    public function jsonp($strJsonCallback, $arrData = null, $intOptions = JSON_UNESCAPED_UNICODE, $strCharset = 'utf-8') {
        if ($this->checkFlowControl ())
            return $this;
        return $this->jsonCallback ( $strJsonCallback )->json ( $arrData, $intOptions, $strCharset );
    }
    
    /**
     * view 加载视图文件
     *
     * @param string $sFile            
     * @param array $arrOption
     *            charset 编码
     *            content_type 内容类型
     *            return 是否返回
     * @return void|string
     */
    public function view($sFile = '', $arrOption = []) {
        if ($this->checkFlowControl ())
            return $this;
        if (! isset ( $arrOption ['return'] )) {
            $arrOption ['return'] = true;
        }
        if (! empty ( $arrOption ['charset'] )) {
            $this->charset ( $arrOption ['charset'] );
        }
        if (! empty ( $arrOption ['content_type'] )) {
            $this->contentType ( $arrOption ['content_type'] );
        }
        return $this->responseType ( 'view' )->option ( 'file', $sFile )->option ( 'option', $arrOption )->assign ( $arrOption )->message ( isset ( $arrOption ['message'] ) ? $arrOption ['message'] : '' )->header ( 'Cache-control', 'protected' );
    }
    
    /**
     * view 变量赋值
     *
     * @param mixed $mixName            
     * @param mixed $mixValue            
     * @return $this
     */
    public function assign($mixName, $mixValue = null) {
        if ($this->checkFlowControl ())
            return $this;
        $this->objView->assign ( $mixName, $mixValue );
        return $this;
    }
    
    /**
     * 正确返回消息
     *
     * @param string $sMessage
     *            消息
     * @param array $arrOption
     *            charset 编码
     *            content_type 内容类型
     *            return 是否返回
     *            url 跳转 url 地址
     *            time 停留时间
     * @return json
     */
    public function success($sMessage = '', $arrOption = []) {
        if ($this->checkFlowControl ())
            return $this;
        $arrOption = array_merge ( [ 
                'message' => $sMessage ?  : __ ( '操作成功' ),
                'url' => '',
                'time' => 1 
        ], $arrOption );
        
        return $this->view ( $this->getOption ( 'action_success' ), $arrOption );
    }
    
    /**
     * 错误返回消息
     *
     * @param string $sMessage
     *            消息
     * @param array $arrOption
     *            charset 编码
     *            content_type 内容类型
     *            return 是否返回
     *            url 跳转 url 地址
     *            time 停留时间
     * @return json
     */
    public function error($sMessage = '', $arrOption = []) {
        if ($this->checkFlowControl ())
            return $this;
        $arrOption = array_merge ( [ 
                'message' => $sMessage ?  : __ ( '操作失败' ),
                'url' => '',
                'time' => 3 
        ], $arrOption );
        
        return $this->view ( $this->getOption ( 'action_fail' ), $arrOption );
    }
    
    /**
     * 路由 URL 跳转
     *
     * @param string $sUrl            
     * @param array $arrOption
     *            make 是否使用 url 生成地址
     *            params url 额外参数
     *            message 消息
     *            time 停留时间，0表示不停留
     * @return void
     */
    public function redirect($sUrl, $arrOption = []) {
        if ($this->checkFlowControl ())
            return $this;
        assert::string ( $sUrl );
        return $this->responseType ( 'redirect' )->option ( 'redirect_url', $sUrl )->option ( 'option', $arrOption );
    }
    
    /**
     * xml
     *
     * @param mixed $arrData            
     * @param string $strCharset            
     * @return $this
     */
    public function xml($arrData = null, $strCharset = 'utf-8') {
        if ($this->checkFlowControl ())
            return $this;
        if (is_array ( $arrData )) {
            $this->data ( $arrData );
        }
        return $this->responseType ( 'xml' )->contentType ( 'text/xml' )->charset ( $strCharset );
    }
    
    /**
     * 下载文件
     *
     * @param string $sFileName            
     * @param string $sDownName            
     * @param array $arrHeader            
     * @return $this
     */
    public function download($sFileName, $sDownName = '', array $arrHeader = []) {
        if ($this->checkFlowControl ())
            return $this;
        if (! $sDownName) {
            $sDownName = basename ( $sFileName );
        } else {
            $sDownName = $sDownName . '.' . filesystem::getExtension ( $sFileName );
        }
        return $this->downloadAndFile ( $sFileName, $arrHeader )->header ( 'Content-Disposition', 'attachment;filename=' . $sDownName );
    }
    
    /**
     * 读取文件
     *
     * @param string $sFileName            
     * @param array $arrHeader            
     * @return $this
     */
    public function file($sFileName, array $arrHeader = []) {
        if ($this->checkFlowControl ())
            return $this;
        return $this->downloadAndFile ( $sFileName, $arrHeader )->header ( 'Content-Disposition', 'inline;filename=' . basename ( $sFileName ) );
    }
    
    /**
     * 页面输出类型
     *
     * @param string $strContentType            
     * @param string $strCharset            
     * @return $this
     */
    protected function contentTypeAndCharset($strContentType, $strCharset = 'utf-8') {
        return $this->header ( 'Content-Type', $strContentType . '; charset=' . $strCharset );
    }
    
    /**
     * 下载或者读取文件
     *
     * @param string $sFileName            
     * @param array $arrHeader            
     * @return $this
     */
    protected function downloadAndFile($sFileName, array $arrHeader = []) {
        if (! is_file ( $sFileName )) {
            throw new InvalidArgumentException ( __ ( '读取的文件不存在' ) );
        }
        $sFileName = realpath ( $sFileName );
        
        // 读取类型
        $resFinfo = finfo_open ( FILEINFO_MIME );
        $strMimeType = finfo_file ( $resFinfo, $sFileName );
        finfo_close ( $resFinfo );
        
        $arrHeader = array_merge ( [ 
                'Cache-control' => 'max-age=31536000',
                'Content-Encoding' => 'none',
                'Content-type' => $strMimeType,
                'Content-Length' => filesize ( $sFileName ) 
        ], $arrHeader );
        $this->responseType ( 'file' )->headers ( $arrHeader )->option ( 'file_name', $sFileName );
        
        return $this;
    }
    
    /**
     * PHP 变量转为字符串
     *
     * @param mixed $mixVar            
     * @return string
     */
    protected function varString($mixVar) {
        if (! is_scalar ( $mixVar ) && ! is_array ( $mixVar )) {
            ob_start ();
            print_r ( $mixVar );
            $mixVar = ob_get_contents ();
            ob_end_clean ();
        }
        return $mixVar;
    }
}
