<?php
// [$QueryPHP] The PHP Framework For Code Poem As Free As Wind. <Query Yet Simple>
// ©2010-2017 http://queryphp.com All rights reserved.
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

/**
 * http.register 服务提供者
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2017.05.12
 * @version 1.0
 */
return [ 
        'singleton@request' => [ 
                'queryyetsimple\http\request',
                function ($oProject) {
                    return new queryyetsimple\http\request ( $oProject ['session']->connect (), $oProject ['cookie'], [ 
                            'var_method' => $oProject ['option'] ['var_method'],
                            'var_ajax' => $oProject ['option'] ['var_ajax'],
                            'var_pjax' => $oProject ['option'] ['var_pjax'] 
                    ] );
                } 
        ],
        'singleton@response' => [ 
                'queryyetsimple\http\response',
                function ($oProject) {
                    return new queryyetsimple\http\response ( $oProject ['router'], $oProject ['view'], $oProject ['session'], $oProject ['cookie'], [ 
                            'action_fail' => $oProject ['option'] ['view\action_fail'],
                            'action_success' => $oProject ['option'] ['view\action_success'],
                            'default_response' => $oProject ['option'] ['default_response'] 
                    ] );
                } 
        ] 
];
