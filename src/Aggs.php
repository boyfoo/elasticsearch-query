<?php

namespace Boyfoo\ElasticsearchSql;

use Boyfoo\ElasticsearchSql\Grammars\AggsGrammar;

class Aggs
{
    public function terms($column, $size = 10)
    {

    }

    /**
     * @return array
     */
    public function toArray()
    {
        return (new AggsGrammar($this))->toArray();
    }

    /**
     * 构建当前类实例
     * @return static
     */
    public static function create()
    {
        return (new static());
    }
}