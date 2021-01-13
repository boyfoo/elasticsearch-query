<?php

namespace Boyfoo\ElasticsearchSql\Support;

use Boyfoo\ElasticsearchSql\Query;

class Resolve
{
    /**
     * @param $closure
     * @return Query
     */
    public static function closureToQuery($closure)
    {
        $query = new Query();
        $closure($query);
        return $query;
    }
}