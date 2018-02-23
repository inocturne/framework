<?php
/*
 * This file is part of the ************************ package.
 * ##########################################################
 * #   ____                          ______  _   _ ______   #
 * #  /     \       ___  _ __  _   _ | ___ \| | | || ___ \  #
 * # |   (  ||(_)| / _ \| '__|| | | || |_/ /| |_| || |_/ /  #
 * #  \____/ |___||  __/| |   | |_| ||  __/ |  _  ||  __/   #
 * #       \__   | \___ |_|    \__  || |    | | | || |      #
 * #     Query Yet Simple      __/  |\_|    |_| |_|\_|      #
 * #                          |___ /  Since 2010.10.03      #
 * ##########################################################
 *
 * The PHP Framework For Code Poem As Free As Wind. <Query Yet Simple>
 * (c) 2010-2018 http://queryphp.com All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Queryyetsimple\Http;

use Countable;
use ArrayIterator;
use JsonSerializable;
use IteratorAggregate;
use Queryyetsimple\{
    Support\IJson,
    Support\IArray
};

/**
 * http bag
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2018.02.19
 * @version 1.0
 */
class Bag implements IArray, IJson, Countable, IteratorAggregate, JsonSerializable
{

    /**
     * 元素合集
     *
     * @var array
     */
    protected $elements = [];

    /**
     * 构造函数
     * 
     * @param array $elements
     * @return void 
     */
    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    /**
     * 取回元素
     *
     * @return array
     */
    public function all()
    {
        return $this->elements;
    }

    /**
     * 返回元素键值
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->elements);
    }

    /**
     * 替换当前所有元素
     *
     * @param array $elements
     * @return void
     */
    public function replace(array $elements = [])
    {
        $this->elements = $elements;
    }

    /**
     * 新增元素
     *
     * @param array $elements
     * @return void
     */
    public function add(array $elements = [])
    {
        $this->elements = array_replace($this->elements, $elements);
    }

    /**
     * 取回元素值
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->filter($key, $default);
    }

    /**
     * 设置元素值
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->elements[$key] = $value;
    }

    /**
     * 判断是否存在元素值
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->elements);
    }

    /**
     * 删除元素值
     *
     * @param string $key
     * @return void
     */
    public function remove($key)
    {
        if ($this->has($key)) {
            unset($this->elements[$key]);
        }
    }

    /**
     * 获取过滤变量
     *
     * @param string $key
     * @param mixed $default
     * @param array|sting|int $filter
     * @param array $options
     * @return mixed
     */
    public function filter($key, $default = null, $filter = null, array $options = [])
    {
        $filter = $this->parseFilter($filter);

        list($key, $filter) = $this->parseKeyFilter($key, $filter);

        if (strpos($key, '\\') !== false) {
            $keyOld = $key;
            list($key) = explode('\\', $key);
        }

        $result = $this->has($key) ? $this->elements[$key] : $default;

        if ($filter) {
            $options = $this->formatOptions($result, $options);

            $result = $this->filterValue($result, $default, $filter, $options);
        }

        if (isset($keyOld)) {
            return $this->getPartData($keyOld, $result);
        } else {
            return $result;
        }
    }

    /**
     * 实现 Countable::count
     *
     * @return int
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * 对象转数组
     *
     * @return array
     */
    public function toArray()
    {
        return $this->elements();
    }

    /**
     * 实现 JsonSerializable::jsonSerialize
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * 对象转 JSON
     *
     * @param integer $option
     * @return string
     */
    public function toJson($option = JSON_UNESCAPED_UNICODE)
    {
        return json_encode($this->jsonSerialize(), $option);
    }

    /**
     * 实现 IteratorAggregate::getIterator
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->elements);
    }

    /**
     * 魔术方法 __toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }


    /**
     * 分析键值和过滤器
     * 
     * @param string $key
     * @param array $filter
     * @return array
     */
    protected function parseKeyFilter($key, array $filter)
    {
        if (strpos($key, '|') !== false) {
            $temp = explode('|', $key);
            $key = array_shift($temp);
            $filter = array_merge($filter, $temp);
        }

        return [$key, $filter];
    }

    /**
     * 分析过滤器
     *
     * @param string|array $filter
     * @return array
     */
    protected function parseFilter($filter = null)
    {
        if (is_null($filter)) {
            return [];
        }

        return is_array($filter) ? $filter : func_get_args();
    }

    /**
     * 过滤值
     *
     * @param mixed $value
     * @param mixed $default
     * @param array $filters
     * @param array $options
     * @return mixed
     */
    protected function filterValue($value, $default, $filters, array $options = [])
    {
        foreach ($filters as $item) {
            if (strpos($item, '=') !== false) {
                $value = $this->filterValueWithFunc($value, $item);
            } elseif (is_callable($item)) {
                $value = $this->filterValueWithCallable($value, $item);
            } elseif (is_scalar($value) && ! empty($item)) {
                $value = $this->filterValueWithFilterVar($value, $item, $options);

                if (false === $value) {
                    $value = $default;
                    break;
                }
            }
        }

        return $value;
    }

    /**
     * 使用函数过滤值
     *
     * @param mixed $value
     * @param string $filter
     * @return mixed
     */
    protected function filterValueWithFunc($value, $filter)
    {
        list($filter, $extend) = explode('=', $filter);

        if ($filter == 'default') {
            $evals = "\$value = '" . ($value ? '1' : '') . "' ?: '" . $extend . "';";
        } elseif ($extend) {
            if (strstr($extend, '**')) {
                $extend = str_replace('**', '$value', $extend);
                $evals = "\$value = {$filter}({$extend});";
            } else {
                $evals = "\$value = {$filter}(\$value, {$extend});";
            }
        }

        eval($evals);

        return $value;
    }

    /**
     * 使用回调过滤值
     *
     * @param mixed $value
     * @param string $filter
     * @return mixed
     */
    protected function filterValueWithCallable($value, $filter)
    {
        $value = call_user_func($filter, $value);

        return $value;
    }

    /**
     * 使用 filter_var 过滤值
     *
     * @param mixed $value
     * @param mixed $filter
     * @param array $options
     * @see http://php.net/manual/en/function.filter-var.php
     * @return mixed
     */
    protected function filterValueWithFilterVar($value, $filter, array $options)
    {
        $value = filter_var($value, $this->parseFilterId($filter), $options);

        return $value;
    }

    /**
     * 分析转换 filter_var 参数
     *
     * @param mixed $filter
     * @return int
     */
    protected function parseFilterId($filter)
    {
        $filter = $this->isInt($filter) ? $filter : filter_id($filter);

        return $filter;
    }

    /**
     * 判断字符串是否为数字
     *
     * @param mixed $value
     * @since bool
     */
    protected function isInt($value)
    {
        if (is_int($value)) {
            return true;
        }

        return ctype_digit(strval($value));
    }

    /**
     * 格式化参数
     *
     * @param mixed $value
     * @param mixed $options
     * @since array
     */
    protected function formatOptions($value, $options)
    {
        if (! is_array($options) && $options) {
            $options = ['flags' => $options];
        }

        if (is_array($value) && ! isset($options['flags'])) {
            $options['flags'] = FILTER_REQUIRE_ARRAY;
        }

        return $options;
    }

    /**
     * 返回部分数组数据
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function getPartData($key, $value)
    {
        list($key, $name) = explode('\\', $key);
        $default = $value;

        if (is_array($value)) {
            $parts = explode('.', $name);

            foreach ($parts as $item) {
                if (! isset($value[$item])) {
                    return $default;
                }
                $value = &$value[$item];
            }

            return $value;
        } else {
            return $default;
        }
    }
}
