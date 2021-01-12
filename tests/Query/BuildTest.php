<?php

namespace Boyfoo\ElasticsearchSql\Tests\Query;

use Boyfoo\ElasticsearchSql\Query\Build;
use PHPUnit\Framework\TestCase;

class BuildTest extends TestCase
{
    public function testTerm()
    {
        $value = 100;
        $key = 'price';

        $query = new Build();
        $sql = $query->term($key, $value);

        $count = $sql->getCount();
        $this->assertEquals(1, $count['term']);

        $term = $sql->getWheres()[0];
        $this->assertEquals('term', $term['type']);
        $this->assertEquals($value, $term['value']);
        $this->assertEquals("=", $term['operator']);
        $this->assertEquals("and", $term['boolean']);
        $this->assertEquals($key, $term['column']);
    }

    public function testTermNot()
    {
        $value = 100;
        $key = 'price';

        $query = new Build();
        $sql = $query->notTerm($key, $value);

        $count = $sql->getCount();
        $this->assertEquals(1, $count['term']);

        $term = $sql->getWheres()[0];
        $this->assertEquals('term', $term['type']);
        $this->assertEquals($value, $term['value']);
        $this->assertEquals("!=", $term['operator']);
        $this->assertEquals("and", $term['boolean']);
        $this->assertEquals($key, $term['column']);
    }


    public function testTermShould()
    {
        $value = 100;
        $key = 'no';

        $sql = new Build();
        $sql->shouldTerm($key, $value);


        $count = $sql->getCount();
        $this->assertEquals(1, $count['term']);

        $term = $sql->getWheres()[0];
        $this->assertEquals('term', $term['type']);
        $this->assertEquals($value, $term['value']);
        $this->assertEquals("=", $term['operator']);
        $this->assertEquals("or", $term['boolean']);
        $this->assertEquals($key, $term['column']);
    }

    public function testTerms()
    {
        $query = new Build();
        $query->terms("price", 100);
        $query->terms("price", [200, 300]);
        $query->terms("no", 10010);

        $this->assertEquals(3, $query->getCount()['terms']);

        $wheres = $query->getWheres();

        foreach ($wheres as $k => $v) {

            $this->assertEquals('terms', $v['type']);
            $this->assertEquals("=", $v['operator']);
            $this->assertEquals("and", $v['boolean']);

            if (0 === $k) {
                $this->assertEquals([100], $v['value']);
                $this->assertEquals('price', $v['column']);
            }

            if (1 === $k) {
                $this->assertEquals([200, 300], $v['value']);
                $this->assertEquals('price', $v['column']);
            }

            if (2 === $k) {
                $this->assertEquals([10010], $v['value']);
                $this->assertEquals('no', $v['column']);
            }
        }
    }

    public function testTermsNot()
    {
        $query = new Build();
        $query->notTerms("price", 100);
        $query->notTerms("price", [200, 300]);
        $query->notTerms("no", 10010);

        $this->assertEquals(3, $query->getCount()['terms']);

        $wheres = $query->getWheres();

        foreach ($wheres as $k => $v) {

            $this->assertEquals('terms', $v['type']);
            $this->assertEquals("!=", $v['operator']);
            $this->assertEquals("and", $v['boolean']);

            if (0 === $k) {
                $this->assertEquals([100], $v['value']);
                $this->assertEquals('price', $v['column']);
            }

            if (1 === $k) {
                $this->assertEquals([200, 300], $v['value']);
                $this->assertEquals('price', $v['column']);
            }

            if (2 === $k) {
                $this->assertEquals([10010], $v['value']);
                $this->assertEquals('no', $v['column']);
            }
        }
    }

    public function testTermsShould()
    {
        $query = new Build();
        $query->shouldTerms("price", 100);
        $query->shouldTerms("price", [200, 300]);
        $query->shouldTerms("no", 10010);

        $this->assertEquals(3, $query->getCount()['terms']);

        $wheres = $query->getWheres();

        foreach ($wheres as $k => $v) {

            $this->assertEquals('terms', $v['type']);
            $this->assertEquals("=", $v['operator']);
            $this->assertEquals("or", $v['boolean']);

            if (0 === $k) {
                $this->assertEquals([100], $v['value']);
                $this->assertEquals('price', $v['column']);
            }

            if (1 === $k) {
                $this->assertEquals([200, 300], $v['value']);
                $this->assertEquals('price', $v['column']);
            }

            if (2 === $k) {
                $this->assertEquals([10010], $v['value']);
                $this->assertEquals('no', $v['column']);
            }
        }
    }

    public function testMatch()
    {
        $query = new Build();
        $query->match("name", "你好");

        $this->assertEquals(1, $query->getCount()['match']);

        $term = $query->getWheres()[0];
        $this->assertEquals('match', $term['type']);
        $this->assertEquals("你好", $term['value']);
        $this->assertEquals("=", $term['operator']);
        $this->assertEquals("and", $term['boolean']);
        $this->assertEquals("name", $term['column']);

        $query = new Build();
        $query->shouldMatch("name", "你好");
        $this->assertEquals(1, $query->getCount()['match']);

        $term = $query->getWheres()[0];
        $this->assertEquals('match', $term['type']);
        $this->assertEquals("你好", $term['value']);
        $this->assertEquals("=", $term['operator']);
        $this->assertEquals("or", $term['boolean']);
        $this->assertEquals("name", $term['column']);

        $query = new Build();
        $query->notMatch("name", "你好");
        $this->assertEquals(1, $query->getCount()['match']);

        $term = $query->getWheres()[0];
        $this->assertEquals('match', $term['type']);
        $this->assertEquals("你好", $term['value']);
        $this->assertEquals("!=", $term['operator']);
        $this->assertEquals("and", $term['boolean']);
        $this->assertEquals("name", $term['column']);
    }

    public function testRange()
    {
        $query = new Build();
        $query->range("price", ['>=' => 100, '<=' => 200]);
        $this->assertEquals(1, $query->getCount()['range']);

        $term = $query->getWheres()[0];
        $this->assertEquals('range', $term['type']);
        $this->assertEquals(['gte' => 100, 'lte' => 200], $term['value']);
        $this->assertEquals("=", $term['operator']);
        $this->assertEquals("and", $term['boolean']);
        $this->assertEquals("price", $term['column']);
    }

    public function testRangeShould()
    {
        $query = new Build();
        $query->shouldRange("price", ['>=' => 100, '<=' => 200]);
        $this->assertEquals(1, $query->getCount()['range']);

        $term = $query->getWheres()[0];
        $this->assertEquals('range', $term['type']);
        $this->assertEquals(['gte' => 100, 'lte' => 200], $term['value']);
        $this->assertEquals("=", $term['operator']);
        $this->assertEquals("or", $term['boolean']);
        $this->assertEquals("price", $term['column']);
    }

    public function testRangeNot()
    {
        $query = new Build();
        $query->notRange("price", ['>=' => 100, '<=' => 200]);
        $this->assertEquals(1, $query->getCount()['range']);

        $term = $query->getWheres()[0];
        $this->assertEquals('range', $term['type']);
        $this->assertEquals(['gte' => 100, 'lte' => 200], $term['value']);
        $this->assertEquals("!=", $term['operator']);
        $this->assertEquals("and", $term['boolean']);
        $this->assertEquals("price", $term['column']);
    }
}
