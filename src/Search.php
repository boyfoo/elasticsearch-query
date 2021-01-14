<?php

namespace Boyfoo\ElasticsearchSql;

use Boyfoo\ElasticsearchSql\Grammars\SearchGrammar;
use Boyfoo\ElasticsearchSql\Support\Row;
use Boyfoo\ElasticsearchSql\Traits\DslParameter;
use Closure;

class Search
{
    use DslParameter;

    protected $index;

    protected $type = '_doc';

    protected $query;

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

    /**
     * @param Aggs|Closure|Row ...$aggs
     * @return $this
     */
    public function aggs(...$aggs)
    {
        $this->aggs = $aggs;

        return $this;
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