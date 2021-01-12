<?php

namespace Boyfoo\ElasticsearchSql\Grammars;


use Boyfoo\ElasticsearchSql\Query\Build;
use Boyfoo\ElasticsearchSql\Query\Expression;
use Boyfoo\ElasticsearchSql\Support\Resolve;
use Closure;

class BoolGrammar
{
    protected $search;

    protected $sql = [
        'must' => [],
        'must_not' => [],
        'should' => [],
    ];

    public function __construct(Build $search)
    {
        $this->search = $search;
    }

    /**
     * @return array
     * [
     *  'bool' => []
     * ]
     */
    public function toArray()
    {
        foreach ($this->search->getWheres() as $where) {
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

    protected function expression($where)
    {
        $this->sql[$this->partition($where)][] = [
            $where['type'] => $this->getExpressionValue($where['column'])
        ];
    }

    protected function getExpressionValue(Expression $expression)
    {
        return $expression->getValue();
    }

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

    protected function range($where)
    {
        $this->sql[$this->partition($where)][] = [
            'range' => [
                $where['column'] => $where['value']
            ]
        ];
    }

    protected function match($where)
    {
        $this->sql[$this->partition($where)][] = [
            'match' => [
                $where['column'] => $where['value']
            ]
        ];
    }

    protected function terms($where)
    {
        $this->sql[$this->partition($where)][] = [
            'terms' => [
                $where['column'] => $where['value']
            ]
        ];
    }

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

    protected function partition($where)
    {
        if ('=' === $where['operator']) {
            return 'and' == $where['boolean'] ? 'must' : 'should';
        }

        return 'must_not';
    }
}
