<?php

namespace Boyfoo\ElasticsearchSql\Tests\Grammars;

use Boyfoo\ElasticsearchSql\Aggs;
use PHPUnit\Framework\TestCase;

class AggsGrammarTest extends TestCase
{
    public function testTerms()
    {
        $this->assertEquals(
            ['test_name' => ['terms' => ['field' => 'test_file', 'size' => 4]]],
            Aggs::create()->terms()->size(4)->field('test_file')->name('test_name')->toArray()
        );
        $this->assertEquals(
            ['test_name' => ['terms' => ['field' => 'test_file', 'size' => 4]]],
            Aggs::create()->terms('test_name', 'test_file', 4)->toArray()
        );
    }

    public function testTopHits()
    {
        $this->assertEquals(
            json_decode('{"test_name":{"top_hits":{"size":4,"_source":["no","price"],"sort":[{"price":{"order":"asc"}}]}}}', true),
            Aggs::create()
                ->topHits()
                ->size(4)
                ->name('test_name')
                ->source(['no', 'price'])->sortBy('price')
                ->toArray()
        );

    }
}
