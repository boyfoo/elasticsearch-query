<?php

namespace Boyfoo\ElasticsearchSql\Tests;

use Boyfoo\ElasticsearchSql\Aggs;
use Boyfoo\ElasticsearchSql\Support\Es;
use PHPUnit\Framework\TestCase;

class AggsTest extends TestCase
{
    public function testMain()
    {
        $this->assertTrue(true);


        $aggs = Aggs::create();
        $aggs->terms()
            ->name("***test_category")
            ->field('question_category_id')
            ->size(3)
            ->aggs(
                Es::row([
                    'test' => [
                        'terms' => [
                            "field" => 'question_no',
                            'size' => 5
                        ]
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

//
//        $a = $aggs->toArray();
//        dump($a);
    }
}
