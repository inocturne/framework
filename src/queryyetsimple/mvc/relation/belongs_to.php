<?php
// [$QueryPHP] The PHP Framework For Code Poem As Free As Wind. <Query Yet Simple>
// ©2010-2017 http://queryphp.com All rights reserved.
namespace queryyetsimple\mvc\relation;

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

use queryyetsimple\mvc\interfaces\model;
use queryyetsimple\collection\collection;

/**
 * 关联模型 belongs_to
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2017.09.28
 * @version 1.0
 */
class belongs_to extends relation {
    
    /**
     * 构造函数
     *
     * @param \queryyetsimple\mvc\interfaces\model $objTargetModel            
     * @param \queryyetsimple\mvc\interfaces\model $objSourceModel            
     * @param string $strTargetKey            
     * @param string $strSourceKey            
     * @return void
     */
    public function __construct(model $objTargetModel, model $objSourceModel, $strTargetKey, $strSourceKey) {
        parent::__construct ( $objTargetModel, $objSourceModel, $strTargetKey, $strSourceKey );
    }
    
    /**
     * 关联基础查询条件
     *
     * @return void
     */
    public function addRelationCondition() {
        if (static::$booRelationCondition) {
            $this->objSelect->where ( $this->strTargetKey, $this->getSourceValue () );
        }
    }
    
    /**
     * 匹配关联查询数据到模型
     *
     * @param \queryyetsimple\mvc\interfaces\model[] $arrModel            
     * @param \queryyetsimple\collection\collection $objResult            
     * @param string $strRelation            
     * @return array
     */
    public function matchPreLoad(array $arrModel, collection $objResult, $strRelation) {
        $arrMap = $this->buildMap ( $objResult );
        
        foreach ( $arrModel as &$objModel ) {
            $mixKey = $objModel->getProp ( $this->strSourceKey );
            if (isset ( $arrMap [$mixKey] )) {
                $objModel->setRelationProp ( $strRelation, $arrMap [$mixKey] );
            }
        }
        
        return $arrModel;
    }
    
    /**
     * 设置预载入关联查询条件
     *
     * @param \queryyetsimple\mvc\interfaces\model[] $arrModel            
     * @return void
     */
    public function preLoadCondition(array $arrModel) {
        $this->objSelect->reset ( 'where' )->whereIn ( $this->strTargetKey, $this->getPreLoadModelValue ( $arrModel ) );
    }
    
    /**
     * 取回源模型对应数据
     *
     * @return mixed
     */
    public function getSourceValue() {
        return $this->objSourceModel->getProp ( $this->strSourceKey );
    }
    
    /**
     * 查询关联对象
     *
     * @return mixed
     */
    public function sourceQuery() {
        return $this->objSelect->getOne ();
    }
    
    /**
     * 模型隐射数据
     *
     * @param \queryyetsimple\collection\collection $objResult            
     * @return array
     */
    protected function buildMap(collection $objResult) {
        $arrMap = [ ];
        
        foreach ( $objResult as $objResultModel ) {
            $arrMap [$objResultModel->getProp ( $this->strTargetKey )] = $objResultModel;
        }
        
        return $arrMap;
    }
    
    /**
     * 分析预载入模型中对应的源数据
     *
     * @param \queryyetsimple\mvc\interfaces\model[] $arrModel            
     * @return array
     */
    protected function getPreLoadModelValue(array $arrModel) {
        $arr = [ ];
        
        foreach ( $arrModel as $objModel ) {
            if (! is_null ( $mixTemp = $objModel->getProp ( $this->strSourceKey ) )) {
                $arr [] = $mixTemp;
            }
        }
        
        if (count ( $arr ) == 0) {
            return [ 
                    0 
            ];
        }
        
        return array_values ( array_unique ( $arr ) );
    }
}