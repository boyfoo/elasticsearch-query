<?php


namespace Boyfoo\ElasticsearchSql;


use Boyfoo\ElasticsearchSql\Query\Build;
use Boyfoo\ElasticsearchSql\Grammars\SearchGrammar;
use Boyfoo\ElasticsearchSql\Support\Resolve;
use Closure;

class Search
{
    protected $index;

    protected $type = '_doc';

    protected $query;

    protected $sort;

    protected $source;

    protected $from;

    protected $size;

    /**
     * @param $index
     * @return $this
     */
    public function index($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @param $type
     * @return $this
     */
    public function type($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param Build|Closure $build
     * @return $this
     */
    public function query($build)
    {
        if ($build instanceof Build) {
            $this->query[] = $build;
        } elseif ($build instanceof Closure) {
            $this->query[] = Resolve::closureToQuery($build);
        }

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function from($value)
    {
        $this->from = $value;

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function size($value)
    {
        $this->size = $value;

        return $this;
    }

    /**
     * @param $source
     * @return $this
     */
    public function source($source)
    {
        if (is_bool($source)) {
            $this->source = $source;
        } else {
            $this->source = is_array($source) ? $source : func_get_args();
        }

        return $this;
    }

    /**
     * @param $column
     * @param string|array $value desc
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

        $this->sort[] = $value;

        return $this;
    }

    /**
     * @param $column
     * @return $this
     */
    public function sortByDesc($column)
    {
        return $this->sortBy($column, 'desc');
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getSort()
    {
        return $this->sort;
    }

    public function toArray()
    {
        return (new SearchGrammar($this))->toArray();
    }

    /**
     * @return static
     */
    public static function create()
    {
        return (new static());
    }

    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }
}