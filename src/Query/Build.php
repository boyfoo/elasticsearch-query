<?php


namespace Boyfoo\ElasticsearchSql\Query;

use Boyfoo\ElasticsearchSql\Grammars\BoolGrammar;
use Boyfoo\ElasticsearchSql\Query\Traits\MustNotTrait;
use Boyfoo\ElasticsearchSql\Query\Traits\ShouldTrait;
use Boyfoo\ElasticsearchSql\Support\Resolve;
use Closure;


/**
 * Elasticsearch query bool
 * @package Boyfoo\ElasticsearchSql\Query
 */
class Build
{
    use ShouldTrait, MustNotTrait;

    protected $wheres = [];

    protected $counts = [
        'term' => 0,
        'terms' => 0,
        'range' => 0,
        'match' => 0,
        'bool' => 0
    ];

    protected $operators = [
        '=' => 'eq',
        '>' => 'gt',
        '>=' => 'gte',
        '<' => 'lt',
        '<=' => 'lte',
    ];

    /**
     * @param $column
     * @param $value
     * @param string $operator
     * @param string $boolean
     * @return $this
     */
    public function match($column, $value, $operator = '=', $boolean = 'and')
    {
        return $this->addWheres('match', ...func_get_args());
    }

    /**
     * @param $column
     * @param $value
     * @param $operator
     * @param $boolean
     * @return $this
     */
    public function term($column, $value = null, $operator = '=', $boolean = 'and')
    {
        return $this->addWheres('term', ...func_get_args());
    }

    /**
     * @param $column
     * @param $value
     * @param $operator
     * @param $boolean
     * @return $this
     */
    public function terms($column, $value = null, $operator = '=', $boolean = 'and')
    {
        return $this->addWheres(
            'terms', $column, is_array($value) ? $value : [$value], $operator, $boolean
        );
    }

    /**
     * @param $column
     * @param array $value
     * @param $operator
     * @param $boolean
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
     * @param Build|Closure|Expression $build
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
     * @return array
     */
    public function getWheres()
    {
        return $this->wheres;
    }

    public function toArray()
    {
        return (new BoolGrammar($this))->toArray();
    }

    /**
     * @return static
     */
    public static function create()
    {
        return (new static());
    }
}