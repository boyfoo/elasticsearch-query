<?php

namespace Boyfoo\ElasticsearchSql\Grammars;

use Boyfoo\ElasticsearchSql\Aggs;
use Boyfoo\ElasticsearchSql\Support\Resolve;
use Boyfoo\ElasticsearchSql\Support\Row;
use Closure;

class AggsGrammar
{
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
        $res = $this->{$aggs['type']}($aggs);
        if ($this->hasChildAggs($aggs)) {
            $res[$this->getName($aggs)]['aggs'] = $this->childAggs($aggs['aggs']);
        }
        return $res;
    }

    /**
     * @param $aggs
     * @return bool
     */
    protected function hasChildAggs($aggs) {
        return !is_null($aggs['aggs']) && is_array($aggs['aggs']);
    }

    /**
     * @param $aggs
     * @return array
     */
    protected function childAggs($aggs)
    {
        $res = [];
        foreach ($aggs as $v) {

            if ($v instanceof Closure) {
                $v = Resolve::closureToAggs($v);
            }

            if ($v instanceof Aggs) {
                $res += $v->toArray();
            } elseif ($v instanceof Row) {
                $res += $v->getValue();
            }

        }

        return $res;
    }

    /**
     * @param $aggs
     * @return array
     */
    protected function terms($aggs)
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
}