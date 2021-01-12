<?php


namespace Boyfoo\ElasticsearchSql\Query\Traits;

use Boyfoo\ElasticsearchSql\Query\Build;

trait ShouldTrait
{
    /**
     * @param $column
     * @param $value
     * @return $this
     */
    public function shouldMatch($column, $value)
    {
        return $this->match($column, $value, '=', 'or');
    }

    /**
     * @param $column
     * @param $value
     * @return $this
     */
    public function shouldTerm($column, $value)
    {
        return $this->term($column, $value, '=', 'or');
    }

    /**
     * @param $column
     * @param $value
     * @return $this
     */
    public function shouldTerms($column, $value)
    {
        return $this->terms($column, $value, '=', 'or');
    }

    /**
     * @param $column
     * @param $value array
     * @return $this
     */
    public function shouldRange($column, $value)
    {
        return $this->range($column, $value, '=', 'or');
    }

    /**
     * @param Build|Closure $build
     * @return $this
     */
    public function shouldBool($build)
    {
        return $this->bool($build, '=', 'or');
    }
}