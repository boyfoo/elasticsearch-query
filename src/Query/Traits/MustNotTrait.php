<?php


namespace Boyfoo\ElasticsearchSql\Query\Traits;

use Boyfoo\ElasticsearchSql\Query\Build;

trait MustNotTrait
{
    /**
     * @param $column
     * @param $value
     * @return $this
     */
    public function notMatch($column, $value = null)
    {
        return $this->match($column, $value, '!=', 'and');
    }

    /**
     * @param $column
     * @param $value
     * @return $this
     */
    public function notTerm($column, $value = null)
    {
        return $this->term($column, $value, '!=', 'and');
    }

    /**
     * @param $column
     * @param $value
     * @return $this
     */
    public function notTerms($column, $value = null)
    {
        return $this->terms($column, $value, '!=', 'and');
    }

    /**
     * @param $column
     * @param $value array
     * @return $this
     */
    public function notRange($column, $value = null)
    {
        return $this->range($column, $value, '!=', 'and');
    }

    /**
     * @param Build|Closure $build
     * @return $this
     */
    public function notBool($build)
    {
        return $this->bool($build, '!=', 'and');
    }
}