<?php


namespace Boyfoo\ElasticsearchSql\Grammars;


use Boyfoo\ElasticsearchSql\Query\Build;
use Boyfoo\ElasticsearchSql\Query\Row;
use Boyfoo\ElasticsearchSql\Search;
use Boyfoo\ElasticsearchSql\Support\Resolve;
use Closure;

/**
 * 查询主体解析器
 * Class SearchGrammar
 * @package Boyfoo\ElasticsearchSql\Grammars
 */
class SearchGrammar
{
    protected $search;

    public function __construct(Search $search)
    {
        $this->search = $search;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $sql = [
            'index' => $this->search->getIndex(),
            'type' => $this->search->getType(),
        ];

        $body = [];

        if (!is_null($this->search->getFrom())) {
            $body['from'] = $this->search->getFrom();
        }

        if (!is_null($this->search->getSize())) {
            $body['size'] = $this->search->getSize();
        }

        if (!is_null($this->search->getSource())) {
            $body['_source'] = $this->search->getSource();
        }

        if (!is_null($this->search->getSort())) {
            $body['sort'] = $this->search->getSort();
        }

        if (!is_null($this->search->getQuery())) {
            $build = $this->search->getQuery();

            if ($build instanceof Closure) {
                $build = Resolve::closureToQuery($build);
            }

            if ($build instanceof Row) {
                $body['query'] = $build->getValue();
            } elseif ($build instanceof Build) {
                $body['query'] = $build->toArray();
            }
        }

        $sql['body'] = $body;

        return $sql;
    }
}