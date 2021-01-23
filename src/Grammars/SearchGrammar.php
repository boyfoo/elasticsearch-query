<?php

namespace Boyfoo\ElasticsearchSql\Grammars;

use Boyfoo\ElasticsearchSql\Search;
use Boyfoo\ElasticsearchSql\Support\Resolve;

/**
 * 查询主体解析器
 * Class SearchGrammar
 * @package Boyfoo\ElasticsearchSql\Grammars
 */
class SearchGrammar
{
    protected $searchBuild;

    public function __construct(Search $search)
    {
        $this->searchBuild = $search;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $body = $this->searchBuild->getParameter();

        $build = $this->searchBuild->getQuery();
        if (!is_null($build)) {
            $body['query'] = Resolve::buildQuery($build);
        }

        $build = $this->searchBuild->getAggs();
        if (!is_null($build)) {
            $body['aggs'] = $this->buildAggs($build);
        }

        return [
            'index' => $this->searchBuild->getIndex(),
            'type' => $this->searchBuild->getType(),
            'body' => $body
        ];
    }

    /**
     * @param $builds
     * @return array
     */
    protected function buildAggs($builds)
    {
        $res = [];

        foreach ($builds as $build) {
            $res += Resolve::buildAggs($build);
        }

        return $res;
    }
}