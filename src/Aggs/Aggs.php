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
     * @return static
     */
    public static function create()
    {
        return (new static());
    }
}