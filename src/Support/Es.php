<?php

namespace Boyfoo\ElasticsearchSql\Support;


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