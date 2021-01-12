<?php

namespace Boyfoo\ElasticsearchSql\Tests;

use PHPUnit\Framework\TestCase;

class SearchTest extends TestCase
{
    public function testA()
    {
        $this->assertTrue(true);
        //        $query = new Build();
//        $query->term('subject_id', "12");
//        $query->match("full_text", "如果盈利");
//
//        $sql = Search::create()
//            ->index('rxedu_question')
//            ->type('question')
//            ->query(function (Build $query){
//                $query->term("subject_id", 10);
//            })
//            ->size(2)
//            ->toArray();
//
//        $client = ClientBuilder::create()->setHosts([
//           "127.0.0.1:9200"
//        ])->build();
//
//        $res = $client->search($sql);
////        dump($res);
    }
}
