<?php

namespace Boyfoo\ElasticsearchSql\Traits;

trait DslParameter
{
    /**
     * @var array
     * [
     *  'from' => (int|null)
     *  'size' => (int|null)
     *  'sort' => (array|null)
     *  '_source' => (array|bool)
     * ]
     */
    protected $parameter = [];

    /**
     * 设置from内容
     * @param int $value
     * @return $this
     */
    public function from($value)
    {
        $this->parameter['from'] = $value;

        return $this;
    }

    /**
     * 设置size内容
     * @param int $size
     * @return $this
     */
    public function size($size)
    {
        $this->parameter['size'] = $size;

        return $this;
    }

    /**
     * 设置搜索字段
     * @param $source
     * @return $this
     */
    public function source($source)
    {
        if (is_bool($source)) {
            $this->parameter['_source'] = $source;
        } else {
            $this->parameter['_source'] = is_array($source) ? $source : func_get_args();
        }

        return $this;
    }

    /**
     * 设置排序字段
     * @param string $column 字段
     * @param string $value desc
     * @return $this
     */
    public function sortBy($column, $value = 'asc')
    {
        if (!is_array($value)) {
            $value = [
                $column => [
                    'order' => $value
                ]
            ];
        }

        $this->parameter['sort'][] = $value;

        return $this;
    }

    /**
     * 设置排序字段倒叙
     * @param string $column
     * @return $this
     */
    public function sortByDesc($column)
    {
        return $this->sortBy($column, 'desc');
    }

    /**
     * @return array
     */
    public function getParameter()
    {
        return $this->parameter;
    }
}