<?php

namespace Boyfoo\ElasticsearchSql\Grammars;


use Boyfoo\ElasticsearchSql\Query\Build;
use Boyfoo\ElasticsearchSql\Query\Expression;
use Boyfoo\ElasticsearchSql\Support\Resolve;
use Closure;

/**
 * query查询语句解析器
 * 解析结果为bool
 * Class BoolGrammar
 * @package Boyfoo\ElasticsearchSql\Grammars
 */
class BoolGrammar
{
    protected $query;

    protected $sql = [
        'must' => [],
        'must_not' => [],
        'should' => [],
    ];

    public function __construct(Build $search)
    {
        $this->query = $search;
    }

    /**
     * 返回数据类型的构建结果
     * @return array
     * [
     *  'bool' => []
     * ]
     */
    public function toArray()
    {
        foreach ($this->query->getWheres() as $where) {
            if ($where['column'] instanceof Expression) {
                $this->expression($where);
            } else {
                $this->{$where['type']}($where);
            }
        }

        return ['bool' => array_filter($this->sql, function ($item) {
            return $item;
        })];
    }

    /**
     * 原始表达式构建
     * @param array $where
     */
    protected function expression($where)
    {
        $this->sql[$this->partition($where)][] = [
            $where['type'] => $this->getExpressionValue($where['column'])
        ];
    }

    /**
     * 获取原始表达式内容值
     * @param Expression $expression
     * @return mixed
     */
    protected function getExpressionValue(Expression $expression)
    {
        return $expression->getValue();
    }

    /**
     * term语句构建
     * @param $where
     */
    protected function term($where)
    {
        $this->sql[$this->partition($where)][] = [
            'term' => [
                $where['column'] => [
                    'value' => $where['value']
                ]
            ]
        ];
    }

    /**
     * range语句构建
     * @param $where
     */
    protected function range($where)
    {
        $this->sql[$this->partition($where)][] = [
            'range' => [
                $where['column'] => $where['value']
            ]
        ];
    }

    /**
     * match语句构建
     * @param $where
     */
    protected function match($where)
    {
        $this->sql[$this->partition($where)][] = [
            'match' => [
                $where['column'] => $where['value']
            ]
        ];
    }

    /**
     * terms语句构建
     * @param $where
     */
    protected function terms($where)
    {
        $this->sql[$this->partition($where)][] = [
            'terms' => [
                $where['column'] => $where['value']
            ]
        ];
    }

    /**
     * bool语句构建
     * @param $where
     */
    protected function bool($where)
    {
        $build = $where['column'];

        if ($build instanceof Closure) {
            $build = Resolve::closureToQuery($build);
        }

        if (!($build instanceof Build)) {
            return;
        }

        $this->sql[$this->partition($where)][] = $build->toArray();
    }

    /**
     * 区分搜索块类型
     * @param $where
     * @return string
     */
    protected function partition($where)
    {
        if ('=' === $where['operator']) {
            return 'and' == $where['boolean'] ? 'must' : 'should';
        }

        return 'must_not';
    }
}
