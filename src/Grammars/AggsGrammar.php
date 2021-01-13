<?php

namespace Boyfoo\ElasticsearchSql\Grammars;

use Boyfoo\ElasticsearchSql\Aggs\Aggs;

class AggsGrammar
{
    protected $aggs;

    public function __construct(Aggs $aggs)
    {
        $this->aggs = $aggs;
    }

    public function toArray()
    {
        return [];
    }
}