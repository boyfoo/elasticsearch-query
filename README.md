<h1 align="center"> elasticsearch-query </h1>

<p align="center">:rainbow: ElasticSearch DSL 查询语句构建组件</p>

[![Build Status](https://travis-ci.com/boyfoo/elasticsearch-query.svg?branch=master)](https://travis-ci.com/boyfoo/elasticsearch-query)

## 安装

```shell
$ composer require boyfoo/elasticsearch-query -vvv
```

## 使用

```php
use Boyfoo\ElasticsearchSql\Search;
use Boyfoo\ElasticsearchSql\Query;

// 创建查询
$sql = Search::create()
        ->index('goods')
        ->source(['no', 'price', 'category'])
        ->size(10)
        ->query(function (Query $query){
            $query->match("小米手机")->term('category', '电子产品');
        })
        ->toArray();
```

打印结果

```php
[
  "index" => "goods",
  "type" => "_doc",
  "body" => [
    "_source" => [
      "no", "name", "price", "category"
    ],
    "size" => 10,
    "query" => [
      "bool" => [
        "must" => [
          [
            "match" => [
              "name" => "小米手机"
            ]
          ],
          [
            "term" => [
              "category" => [
                "value" => "电子产品"
              ]
            ]
          ]
        ]
      ]
    ]
  ]
];
```

## License

MIT