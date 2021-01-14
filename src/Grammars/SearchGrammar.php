<?php

namespace Boyfoo\ElasticsearchSql\Grammars;

use Boyfoo\ElasticsearchSql\Aggs;
use Boyfoo\ElasticsearchSql\Query;
use Boyfoo\ElasticsearchSql\Search;
use Boyfoo\ElasticsearchSql\Support\Resolve;
use Boyfoo\ElasticsearchSql\Support\Row;
use Closure;

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
        $body = [];

        $from = $this->searchBuild->getFrom();
        !is_null($from) && $body['from'] = $from;

        $size = $this->searchBuild->getSize();
        !is_null($size) && $body['size'] = $size;

        $source = $this->searchBuild->getSource();
        !is_null($source) && $body['_source'] = $source;

        $sort = $this->searchBuild->getSort();
        !is_null($sort) && $body['sort'] = $sort;

        $build = $this->searchBuild->getQuery();
        if (!is_null($build)) {
            $body['query'] = $this->buildQuery($build);
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
     * @param $build
     * @return array
     */
    protected function buildQuery($build)
    {
        $res = [];
        if ($build instanceof Closure) {
            $build = Resolve::closureToQuery($build);
        }
        if ($build instanceof Row) {
            $res = $build->getValue();
        } elseif ($build instanceof Query) {
            $res = $build->toArray();
        }

        return $res;
    }

    /**
     * @param $builds
     * @return array
     */
    protected function buildAggs($builds)
    {
        $res = [];

        foreach ($builds as $build) {
            if ($build instanceof Closure) {
                $build = Resolve::closureToAggs($build);
            }
            if ($build instanceof Row) {
                $res += $build->getValue();
            } elseif ($build instanceof Aggs) {
                $res += $build->toArray();
            }
        }

        return $res;
    }
}