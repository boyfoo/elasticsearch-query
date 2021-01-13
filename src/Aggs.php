<?php

namespace Boyfoo\ElasticsearchSql;

use Boyfoo\ElasticsearchSql\Grammars\AggsGrammar;
use Boyfoo\ElasticsearchSql\Support\Row;

class Aggs
{
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
     * @return $this
     */
    public function terms()
    {
        return $this->type('terms');
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

    /**
     * 构建当前类实例
     * @return static
     */
    public static function create()
    {
        return (new static());
    }
}