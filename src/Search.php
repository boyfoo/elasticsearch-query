<?php

namespace Boyfoo\ElasticsearchSql;

use Boyfoo\ElasticsearchSql\Grammars\SearchGrammar;
use Boyfoo\ElasticsearchSql\Support\Row;
use Closure;

class Search
{
    protected $index;

    protected $type = '_doc';

    protected $query;

    protected $sort;

    protected $source;

    protected $from;

    protected $size;

    protected $aggs;

    /**
     * 设置文档索引
     * @param string $index
     * @return $this
     */
    public function index($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * 设置文档类型
     * @param string $type
     * @return $this
     */
    public function type($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * 构建查询内query
     * 传入Build实体或闭包或Expression表达式
     * @param Query|Closure|Row $build
     * @return $this
     */
    public function query($build)
    {
        $this->query = $build;

        return $this;
    }

    public function aggs($aggs)
    {
        $this->aggs = $aggs;

        return $this;
    }

    /**
     * 设置from内容
     * @param int $value
     * @return $this
     */
    public function from($value)
    {
        $this->from = $value;

        return $this;
    }

    /**
     * 设置size内容
     * @param int $value
     * @return $this
     */
    public function size($value)
    {
        $this->size = $value;

        return $this;
    }

    /**
     * 设置搜索字段
     * @param $source
     * @return $this
     */
    public function source($source)
    {
        if (is_bool($source)) {
            $this->source = $source;
        } else {
            $this->source = is_array($source) ? $source : func_get_args();
        }

        return $this;
    }

    /**
     * 设置排序字段
     * @param string $column 字段
     * @param string $value desc
     * @return $this
     */
    public function sortBy($column, $value = 'asc')
    {
        if (!is_array($value)) {
            $value = [
                $column => [
                    'order' => $value
                ]
            ];
        }

        $this->sort[] = $value;

        return $this;
    }

    /**
     * 设置排序字段倒叙
     * @param string $column
     * @return $this
     */
    public function sortByDesc($column)
    {
        return $this->sortBy($column, 'desc');
    }

    /**
     * 获取当前查询文档索引
     * @return string|null
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * 获取当前查询文档类型
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * 获取当前查询from值
     * @return int|null
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * 获取当前查询size值
     * @return int|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * 获取当前查询字段
     * @return array|null
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * 获当前查询构建的query
     * @return Query|Closure|Row|null
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return array|null
     */
    public function getAggs()
    {
        return $this->aggs;
    }

    /**
     * 获取当前查询排序
     * @return array|null
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * 打印查询语句构建结果
     * @return array
     */
    public function toArray()
    {
        return (new SearchGrammar($this))->toArray();
    }

    /**
     * 创建查询实例
     * @return static
     */
    public static function create()
    {
        return (new static());
    }

    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }
}