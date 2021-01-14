<?php

namespace Boyfoo\ElasticsearchSql\Support;

class Str
{
    public static function studly($value)
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return str_replace(' ', '', $value);
    }
}