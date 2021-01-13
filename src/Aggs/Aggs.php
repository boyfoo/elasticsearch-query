<?php

namespace Boyfoo\ElasticsearchSql\Aggs;

use Boyfoo\ElasticsearchSql\Grammars\AggsGrammar;

class Aggs
{
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