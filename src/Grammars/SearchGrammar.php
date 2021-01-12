<?php


namespace Boyfoo\ElasticsearchSql\Grammars;


use Boyfoo\ElasticsearchSql\Query\Build;
use Boyfoo\ElasticsearchSql\Search;

class SearchGrammar
{
    protected $search;

    public function __construct(Search $search)
    {
        $this->search = $search;
    }

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

        if (!is_null($this->search->getQuery()) && is_array($this->search->getQuery())) {

            foreach ($this->search->getQuery() as $queryBuild) {
                /**
                 * @var Build $queryBuild
                 */

                $body['query'] = $queryBuild->toArray();
            }
        }

        $sql['body'] = $body;

        return $sql;
    }
}