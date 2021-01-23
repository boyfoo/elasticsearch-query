<?php

namespace Boyfoo\ElasticsearchSql\Grammars;

use Boyfoo\ElasticsearchSql\Aggs;
use Boyfoo\ElasticsearchSql\Support\Resolve;
use Boyfoo\ElasticsearchSql\Support\Row;
use Boyfoo\ElasticsearchSql\Support\Str;
use Closure;

class AggsGrammar
{
    /**
     * @var Aggs
     */
    protected $aggsBuild;

    /**
     * 默认size
     * @var int
     */
    public static $defaultSize = 10;

    public function __construct(Aggs $aggs)
    {
        $this->aggsBuild = $aggs;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $aggs = $this->aggsBuild->getCollect();

        $method = 'resolve' . Str::studly($aggs['type']);

        if (!method_exists($this, $method)) {
            return [];
        }

        $aggs['parameter'] = $this->aggsBuild->getParameter();

        $res = $this->{$method}($aggs);

        if ($res) {
            $this->hasChildAggs($aggs) && $res[$this->getName($aggs)]['aggs'] = $this->resolveChildAggs($aggs['aggs']);

            !is_null($aggs['filter']) && $res[$this->getName($aggs)]['filter'] = Resolve::buildQuery($aggs['filter']);
        }

        return $res;
    }

    /**
     * @param $aggs
     * @return array
     */
    protected function resolveTerms($aggs)
    {
        return [
            $this->getName($aggs) => [
                $aggs['type'] => [
                    "field" => $aggs['field'],
                    'size' => $this->getSize($aggs)
                ]
            ]
        ];
    }

    protected function resolveTopHits($aggs)
    {
        return [
            $this->getName($aggs) => [
                $aggs['type'] => array_merge([
                    'size' => $this->getSize($aggs)
                ], $aggs['parameter'])
            ]
        ];
    }

    /**
     * @param $aggs
     * @return string
     */
    protected function getName($aggs)
    {
        return $aggs['name'] ?: $aggs['field'];
    }

    /**
     * @param $aggs
     * @return int
     */
    protected function getSize($aggs)
    {
        return $aggs['size'] ?: static::$defaultSize;
    }

    /**
     * @param $aggs
     * @return bool
     */
    protected function hasChildAggs($aggs)
    {
        return !is_null($aggs['aggs']) && is_array($aggs['aggs']);
    }

    /**
     * @param $aggs
     * @return array
     */
    protected function resolveChildAggs($aggs)
    {
        $res = [];

        foreach ($aggs as $v) {
            $res += Resolve::buildAggs($v);
        }

        return $res;
    }
}