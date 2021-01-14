<?php

namespace Boyfoo\ElasticsearchSql;

use Boyfoo\ElasticsearchSql\Grammars\AggsGrammar;
use Boyfoo\ElasticsearchSql\Support\Row;
use Boyfoo\ElasticsearchSql\Traits\DslParameter;

class Aggs
{
    use DslParameter;

    protected $collect = [
        'type' => null,
        'field' => null,
        'size' => null,
        'name' => null,
        'aggs' => null,
        'filter' => null
    ];

    /**
     * @param $name
     * @return $this
     */
    public function name($name)
    {
        $this->collect['name'] = $name;

        return $this;
    }

    /**
     * @param $field
     * @return $this
     */
    public function field($field)
    {
        $this->collect['field'] = $field;

        return $this;
    }

    /**
     * @param $size
     * @return $this
     */
    public function size($size)
    {
        $this->collect['size'] = $size;

        return $this;
    }

    /**
     * @param string|null $name
     * @param string|null $field
     * @param int|null $size
     * @return $this
     */
    public function terms($name = null, $field = null, $size = null)
    {
        $this->buildParams(compact('name', 'field', 'size'));

        return $this->type('terms');
    }

    /**
     * @param string|null $name
     * @param int|null $size
     * @param array|bool|null $source
     * @return $this
     */
    public function topHits($name = null, $size = null, $source = null)
    {
        $this->buildParams(compact('name', 'size', 'source'));

        return $this->type('top_hits');
    }

    /**
     * @param $type
     * @return $this
     */
    public function type($type)
    {
        $this->collect['type'] = $type;

        return $this;
    }

    /**
     * @param Aggs|Closure|Row ...$aggs
     * @return $this
     */
    public function aggs(...$aggs)
    {
        $this->collect['aggs'] = $aggs;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return (new AggsGrammar($this))->toArray();
    }

    public function getCollect()
    {
        return $this->collect;
    }

    protected function buildParams($params)
    {
        foreach ($params as $k => $v) {
            if (!is_null($v) && method_exists($this, $k)) {
                $this->{$k}($v);
            }
        }
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