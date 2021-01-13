<?php

namespace Boyfoo\ElasticsearchSql\Support;

use Boyfoo\ElasticsearchSql\Aggs;
use Boyfoo\ElasticsearchSql\Query;

class Resolve
{
    /**
     * @param $closure
     * @return Query
     */
    public static function closureToQuery($closure)
    {
        $query = Query::create();
        $closure($query);
        return $query;
    }

    /**
     * @param $closure
     * @return Aggs
     */
    public static function closureToAggs($closure)
    {
        $aggs = Aggs::create();
        $closure($aggs);
        return $aggs;
    }
}