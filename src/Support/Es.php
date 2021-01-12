<?php


namespace Boyfoo\ElasticsearchSql\Support;


use Boyfoo\ElasticsearchSql\Query\Expression;

class Es
{
    /**
     * @param $value
     * @return Expression
     */
    public static function row(array $value)
    {
        return new Expression($value);
    }
}