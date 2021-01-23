<?php

namespace Boyfoo\ElasticsearchSql\Tests\Grammars;

use Boyfoo\ElasticsearchSql\Query as Build;
use Boyfoo\ElasticsearchSql\Search;
use Boyfoo\ElasticsearchSql\Support\Es;
use PHPUnit\Framework\TestCase;

class SearchGrammarTest extends TestCase
{
    public function testSearchBuild()
    {
        $sql = Search::create()
            ->index('test_index')
            ->type('test_type')
            ->source(['no', 'price'])
            ->size(2)
            ->from(10)
            ->sortByDesc('price')
            ->toArray();

        $this->assertEquals('test_index', $sql['index']);
        $this->assertEquals('test_type', $sql['type']);
        $this->assertEquals(10, $sql['body']['from']);
        $this->assertEquals(2, $sql['body']['size']);
        $this->assertEquals(['no', 'price'], $sql['body']['_source']);
        $this->assertEquals(['price' => ['order' => 'desc']], $sql['body']['sort'][0]);
    }

    public function testQuery()
    {
        $query = Build::create()
            ->mustTerm("type", 2)
            ->shouldRange("price", ['>=' => 10, "<" => 20]);

        $sql = Search::create()->query($query)->toArray();

        $str = '{"query":{"bool":{"must":[{"term":{"type":{"value":2}}}],"should":[{"range":{"price":{"gte":10,"lt":20}}}]}}}';

        $this->assertEquals(json_decode($str, true), $sql['body']);
    }

    public function testQueryClosure()
    {
        $sql = Search::create()
            ->query(function (Build $query) {
                $query->mustTerm("type", 2);
                $query->shouldRange("price", ['>=' => 10, "<" => 20]);
            })
            ->toArray();

        $str = '{"query":{"bool":{"must":[{"term":{"type":{"value":2}}}],"should":[{"range":{"price":{"gte":10,"lt":20}}}]}}}';

        $this->assertEquals(json_decode($str, true), $sql['body']);
    }

    public function testQueryBoolClosure()
    {
        $sql = Search::create()
            ->index("test_index")
            ->query(function (Build $build) {
                $build->mustTerm("type", 2);
                $build->notRange("price", ['>=' => 10, "<" => 20]);
                $build->mustBool(function (Build $build) {
                    $build->mustTerm("no", 1001)
                        ->mustTerm("year", 2020);
                });
            })
            ->toArray();

        $this->assertEquals('test_index', $sql['index']);

        $str = '{"query":{"bool":{"must":[{"term":{"type":{"value":2}}},{"bool":{"must":[{"term":{"no":{"value":1001}}},{"term":{"year":{"value":2020}}}]}}],"must_not":[{"range":{"price":{"gte":10,"lt":20}}}]}}}';
        $this->assertEquals(json_decode($str, true), $sql['body']);
    }

    public function testRow()
    {
        $sql = Search::create();

        $sql->index("test01");
        $sql->query(Es::row([
            'term' => [
                'question_no' => [
                    'value' => 64506012
                ]
            ]
        ]));

        $str = '{"index":"test01","type":"_doc","body":{"query":{"term":{"question_no":{"value":64506012}}}}}';
        $this->assertEquals(json_decode($str, true), $sql->toArray());
    }
}
