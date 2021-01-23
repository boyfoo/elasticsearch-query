<?php

namespace Boyfoo\ElasticsearchSql\Support;

use Boyfoo\ElasticsearchSql\Aggs;
use Boyfoo\ElasticsearchSql\Query;
use Closure;

class Resolve
{
    /**
     * @param Closure $closure
     * @return Query
     */
    public static function closureToQuery($closure)
    {
        $query = Query::create();
        $closure($query);
        return $query;
    }

    /**
     * @param Closure $closure
     * @return Aggs
     */
    public static function closureToAggs($closure)
    {
        $aggs = Aggs::create();
        $closure($aggs);
        return $aggs;
    }

    /**
     * @param Closure|Row|Query $build
     * @return array
     */
    public static function buildQuery($build)
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
     * @param Closure|Row|Aggs $build
     * @return array
     */
    public static function buildAggs($build)
    {
        $res = [];

        if ($build instanceof Closure) {
            $build = Resolve::closureToAggs($build);
        }
        if ($build instanceof Row) {
            $res = $build->getValue();
        } elseif ($build instanceof Aggs) {
            $res = $build->toArray();
        }

        return $res;
    }
}