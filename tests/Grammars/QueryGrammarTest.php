<?php

namespace Boyfoo\ElasticsearchSql\Tests\Grammars;

use Boyfoo\ElasticsearchSql\Query as Build;
use Boyfoo\ElasticsearchSql\Grammars\BoolGrammar;
use Boyfoo\ElasticsearchSql\Support\Es;
use PHPUnit\Framework\TestCase;

class QueryGrammarTest extends TestCase
{
    public function testTerm()
    {
        $value = 100;

        $key = "price";

        $query = new Build();

        $query->mustTerm($key, $value);

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
        $query->mustTerm("price", 100);
        $query->mustBool(function (Build $build) {
            $build->mustTerm('type', 1);
            $build->mustTerm('year', 2020);
        });
        $sql = $query->toArray();
        $str = '{"bool":{"must":[{"term":{"price":{"value":100}}},{"bool":{"must":[{"term":{"type":{"value":1}}},{"term":{"year":{"value":2020}}}]}}]}}';
        $this->assertEquals(json_decode($str, true), $sql);

        // should
        $query = Build::create();
        $query->mustTerm("price", 100);
        $query->shouldBool(function (Build $build) {
            $build->mustTerm('type', 1);
            $build->mustTerm('year', 2020);
        });
        $sql = $query->toArray();
        $str = '{"bool":{"must":[{"term":{"price":{"value":100}}}],"should":[{"bool":{"must":[{"term":{"type":{"value":1}}},{"term":{"year":{"value":2020}}}]}}]}}';
        $this->assertEquals(json_decode($str, true), $sql);

        // not
        $query = Build::create();
        $query->mustTerm("price", 100);
        $query->notBool(function (Build $build) {
            $build->mustTerm('type', 1);
            $build->mustTerm('year', 2020);
        });
        $sql = $query->toArray();
        $str = '{"bool":{"must":[{"term":{"price":{"value":100}}}],"must_not":[{"bool":{"must":[{"term":{"type":{"value":1}}},{"term":{"year":{"value":2020}}}]}}]}}';
        $this->assertEquals(json_decode($str, true), $sql);
    }

    public function testRow()
    {
        $q = Build::create();

        $q->mustTerm(Es::row([
            'price' => 100
        ]));

        $q->notTerm(Es::row([
            'type' => 2
        ]));

        $str = '{"bool":{"must":[{"term":{"price":100}}],"must_not":[{"term":{"type":2}}]}}';

        $this->assertEquals(json_decode($str, true), $q->toArray());


        $q = Build::create();
        $q->mustBool(Es::row([
            "must" => [
                [
                    "term" => [
                        'subject_id' => 12
                    ]
                ]
            ]
        ]));

        $str = '{"bool":{"must":[{"bool":{"must":[{"term":{"subject_id":12}}]}}]}}';
        $this->assertEquals(json_decode($str, true), $q->toArray());
    }
}
