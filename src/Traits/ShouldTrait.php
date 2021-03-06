<?php

namespace Boyfoo\ElasticsearchSql\Traits;

use Boyfoo\ElasticsearchSql\Query;
use Boyfoo\ElasticsearchSql\Support\Row;

trait ShouldTrait
{
    /**
     * @param string|Row $column
     * @param $value
     * @return $this
     */
    public function shouldMatch($column, $value = null)
    {
        return $this->match($column, $value, '=', 'or');
    }

    /**
     * @param string|Row $column
     * @param $value
     * @return $this
     */
    public function shouldTerm($column, $value = null)
    {
        return $this->term($column, $value, '=', 'or');
    }

    /**
     * @param string|Row $column
     * @param array $value
     * @return $this
     */
    public function shouldTerms($column, $value = [])
    {
        return $this->terms($column, $value, '=', 'or');
    }

    /**
     * @param string|Row $column
     * @param array $value
     * @return $this
     */
    public function shouldRange($column, $value = [])
    {
        return $this->range($column, $value, '=', 'or');
    }

    /**
     * @param Query|Closure|Row $build
     * @return $this
     */
    public function shouldBool($build)
    {
        return $this->bool($build, '=', 'or');
    }
}