<?php

namespace Boyfoo\ElasticsearchSql\Support;

use Boyfoo\ElasticsearchSql\Query\Row;

class Es
{
    /**
     * @param $value
     * @return Row
     */
    public static function row(array $value)
    {
        return new Row($value);
    }
}