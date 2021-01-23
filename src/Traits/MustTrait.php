<?php

namespace Boyfoo\ElasticsearchSql\Traits;

use Boyfoo\ElasticsearchSql\Query;
use Boyfoo\ElasticsearchSql\Support\Row;

trait MustTrait
{
    /**
     * @param string|Row $column
     * @param $value
     * @return $this
     */
    public function mustMatch($column, $value = null)
    {
        return $this->match($column, $value);
    }

    /**
     * 必须等于该值
     * @param string|Row $column
     * @param $value
     * @return $this
     */
    public function mustTerm($column, $value = null)
    {
        return $this->term($column, $value);
    }

    /**
     * 必须在$value数组值内
     * @param string|Row $column
     * @param array $value
     * @return $this
     */
    public function mustTerms($column, $value = [])
    {
        return $this->terms($column, $value);
    }

    /**
     * 必须在$value范围内
     * @param string|Row $column
     * @param array $value
     * @return $this
     */
    public function mustRange($column, $value = [])
    {
        return $this->range($column, $value);
    }

    /**
     * @param Query|Closure|Row $build
     * @return $this
     */
    public function mustBool($build)
    {
        return $this->bool($build);
    }
}