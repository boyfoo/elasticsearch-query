<?php

namespace Boyfoo\ElasticsearchSql\Query;

use Boyfoo\ElasticsearchSql\Grammars\BoolGrammar;
use Boyfoo\ElasticsearchSql\Query\Traits\MustNotTrait;
use Boyfoo\ElasticsearchSql\Query\Traits\ShouldTrait;
use Closure;

/**
 * Elasticsearch query bool
 * @package Boyfoo\ElasticsearchSql\Query
 */
class Build
{
    use ShouldTrait, MustNotTrait;

    /**
     * 查看内容
     * @var array
     */
    protected $wheres = [];

    /**
     * 查询统计
     * @var int[]
     */
    protected $counts = [
        'term' => 0,
        'terms' => 0,
        'range' => 0,
        'match' => 0,
        'bool' => 0
    ];

    /**
     * 比较符号转换
     * @var string[]
     */
    protected $operators = [
        '=' => 'eq',
        '>' => 'gt',
        '>=' => 'gte',
        '<' => 'lt',
        '<=' => 'lte',
    ];

    /**
     * match 查询
     * @param string|Row $column
     * @param $value
     * @param string $operator
     * @param string $boolean
     * @return $this
     */
    public function match($column, $value = null, $operator = '=', $boolean = 'and')
    {
        return $this->addWheres('match', ...func_get_args());
    }

    /**
     * term语句必须值相等
     * @param string|Row $column
     * @param $value
     * @param string $operator
     * @param string $boolean
     * @return $this
     */
    public function term($column, $value = null, $operator = '=', $boolean = 'and')
    {
        return $this->addWheres('term', ...func_get_args());
    }

    /**
     * 值必须在$value数组内
     * @param string|Row $column
     * @param array $value
     * @param string $operator
     * @param string $boolean
     * @return $this
     */
    public function terms($column, $value = [], $operator = '=', $boolean = 'and')
    {
        return $this->addWheres(
            'terms', $column, is_array($value) ? $value : [$value], $operator, $boolean
        );
    }

    /**
     * 必须在$value范围内
     * @param string|Row $column
     * @param array $value [">=" => $value1, "<" => $value2]
     * @param string $operator
     * @param string $boolean
     * @return $this
     */
    public function range($column, array $value = [], $operator = '=', $boolean = 'and')
    {
        foreach ($value as $k => $v) {
            if (isset($this->operators[$k])) {
                unset($value[$k]);
                $value[$this->operators[$k]] = $v;
            }
        }

        return $this->addWheres('range', $column, $value, $operator, $boolean);
    }

    /**
     * 必须同时满足多个条件 此多个条件为一个must条件
     * @param Build|Closure|Row $build
     * @param string $operator
     * @param string $boolean
     * @return $this
     */
    public function bool($build, $operator = '=', $boolean = 'and')
    {
        return $this->addWheres('bool', $build, null, $operator, $boolean);
    }

    /**
     * @param $type
     * @param $column
     * @param $value
     * @param string $operator
     * @param string $boolean
     * @return $this
     */
    protected function addWheres($type, $column, $value = null, $operator = '=', $boolean = 'and')
    {
        $this->wheres[] = compact('type', 'column', 'value', 'operator', 'boolean');

        $this->addCount($type);

        return $this;
    }

    /**
     * @param $type
     * @param int $num
     */
    protected function addCount($type, $num = 1)
    {
        $this->counts[$type] += $num;
    }

    /**
     * 获取查询数量
     * @return array
     */
    public function getCount()
    {
        return $this->counts;
    }

    /**
     * 查看当查询内容
     * @return array
     */
    public function getWheres()
    {
        return $this->wheres;
    }

    /**
     * 查看构建结果
     * @return array
     */
    public function toArray()
    {
        return (new BoolGrammar($this))->toArray();
    }

    /**
     * 创建当前实例
     * @return static
     */
    public static function create()
    {
        return (new static());
    }
}