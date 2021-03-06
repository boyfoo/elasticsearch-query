<?php

namespace Boyfoo\ElasticsearchSql\Traits;

use Boyfoo\ElasticsearchSql\Query;
use Boyfoo\ElasticsearchSql\Support\Row;

trait MustNotTrait
{
    /**
     * @param string|Row $column
     * @param $value
     * @return $this
     */
    public function notMatch($column, $value = null)
    {
        return $this->match($column, $value, '!=', 'and');
    }

    /**
     * 必须不等于该值
     * @param string|Row $column
     * @param $value
     * @return $this
     */
    public function notTerm($column, $value = null)
    {
        return $this->term($column, $value, '!=', 'and');
    }

    /**
     * 必须不在$value数组值内
     * @param string|Row $column
     * @param array $value
     * @return $this
     */
    public function notTerms($column, $value = [])
    {
        return $this->terms($column, $value, '!=', 'and');
    }

    /**
     * 必须不在$value范围内
     * @param string|Row $column
     * @param array $value
     * @return $this
     */
    public function notRange($column, $value = [])
    {
        return $this->range($column, $value, '!=', 'and');
    }

    /**
     * 必须同时满足所有条件 所有条件为一个must_not条件
     * @param Query|Closure|Row $build
     * @return $this
     */
    public function notBool($build)
    {
        return $this->bool($build, '!=', 'and');
    }
}