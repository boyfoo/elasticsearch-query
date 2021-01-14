<?php

namespace Boyfoo\ElasticsearchSql\Tests;

use Boyfoo\ElasticsearchSql\Aggs;
use Boyfoo\ElasticsearchSql\Support\Es;
use PHPUnit\Framework\TestCase;

class AggsTest extends TestCase
{
    public function testMain()
    {
        $aggs = Aggs::create();
        $aggs->terms()
            ->name("***test_category")
            ->field('question_category_id')
            ->size(3)
            ->aggs(
                Es::row([
                    'test' => [
                        'terms' => ["field" => 'question_no', 'size' => 5]
                    ]
                ]),
                Aggs::create()
                    ->terms()
                    ->field('question_type_id')
                    ->size(3),
                Aggs::create()
                    ->terms()
                    ->field('subject_id'),
                function (Aggs $aggs) {
                    $aggs->size(2)->terms()->field("book_no");
                }
            );

        $str = '{"***test_category":{"terms":{"field":"question_category_id","size":3},"aggs":{"test":{"terms":{"field":"question_no","size":5}},"question_type_id":{"terms":{"field":"question_type_id","size":3}},"subject_id":{"terms":{"field":"subject_id","size":10}},"book_no":{"terms":{"field":"book_no","size":2}}}}}';
        $this->assertEquals(json_decode($str, true), $aggs->toArray());
    }
}
