<?php

namespace Boyfoo\ElasticsearchSql\Grammars;


use Boyfoo\ElasticsearchSql\Query\Build;

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
            $this->{$where['type']}($where);
        }

        return ['bool' => array_filter($this->sql, function ($item) {
            return $item;
        })];
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
        $build = $where['value'];

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
