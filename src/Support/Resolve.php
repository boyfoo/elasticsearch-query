<?php


namespace Boyfoo\ElasticsearchSql\Support;


use Boyfoo\ElasticsearchSql\Query\Build;

class Resolve
{
    /**
     * @param $closure
     * @return Build
     */
    public static function closureToQuery($closure)
    {
        $query = new Build();
        $closure($query);
        return $query;
    }
}