<?php

namespace Boyfoo\ElasticsearchSql\Tests\Grammars;

use Boyfoo\ElasticsearchSql\Query\Build;
use Boyfoo\ElasticsearchSql\Grammars\BoolGrammar;
use PHPUnit\Framework\TestCase;

class QueryGrammarTest extends TestCase
{
    public function testTerm()
    {
        $value = 100;

        $key = "price";

        $query = new Build();

        $query->term($key, $value);

        $qg = new BoolGrammar($query);

        $sql = $qg->toArray();

        $this->assertEquals($sql['bool']['must'][0]['term'][$key]['value'], $value);

        $query = new Build();

        $query->notTerm($key, $value);

        $qg = new BoolGrammar($query);

        $sql = $qg->toArray();

        $this->assertEquals($sql['bool']['must_not'][0]['term'][$key]['value'], $value);

        $query = new Build();

        $query->shouldTerm($key, $value);

        $qg = new BoolGrammar($query);

        $sql = $qg->toArray();

        $this->assertEquals($sql['bool']['should'][0]['term'][$key]['value'], $value);
    }

    public function testBool()
    {
        // must
        $query = Build::create();
        $query->term("price", 100);
        $query->bool(function (Build $build) {
            $build->term('type', 1);
            $build->term('year', 2020);
        });
        $sql = $query->toArray();
        $str = '{"bool":{"must":[{"term":{"price":{"value":100}}},{"bool":{"must":[{"term":{"type":{"value":1}}},{"term":{"year":{"value":2020}}}]}}]}}';
        $this->assertEquals(json_decode($str, true), $sql);

        // should
        $query = Build::create();
        $query->term("price", 100);
        $query->shouldBool(function (Build $build) {
            $build->term('type', 1);
            $build->term('year', 2020);
        });
        $sql = $query->toArray();
        $str = '{"bool":{"must":[{"term":{"price":{"value":100}}}],"should":[{"bool":{"must":[{"term":{"type":{"value":1}}},{"term":{"year":{"value":2020}}}]}}]}}';
        $this->assertEquals(json_decode($str, true), $sql);

        // not
        $query = Build::create();
        $query->term("price", 100);
        $query->notBool(function (Build $build) {
            $build->term('type', 1);
            $build->term('year', 2020);
        });
        $sql = $query->toArray();
        $str = '{"bool":{"must":[{"term":{"price":{"value":100}}}],"must_not":[{"bool":{"must":[{"term":{"type":{"value":1}}},{"term":{"year":{"value":2020}}}]}}]}}';
        $this->assertEquals(json_decode($str, true), $sql);
    }
}
