<h1 align="center"> elasticsearch-query </h1>

<p align="center">:rainbow: ElasticSearch DSL 查询语句构建组件</p>

[![Build Status](https://travis-ci.com/boyfoo/elasticsearch-query.svg?branch=master)](https://travis-ci.com/boyfoo/elasticsearch-query)

## 安装

```shell
$ composer require boyfoo/elasticsearch-query -vvv
```

## 使用

### 示例

使用 `search` 构建器起步创建查询:

```php
use Boyfoo\ElasticsearchSql\Search;
use Boyfoo\ElasticsearchSql\Query;

// 创建查询
$params = Search::create()
            ->index('goods')
            ->source(['no', 'price', 'category'])
            ->size(10)
            ->query(function (Query $query) {
                $query->mustMatch("小米手机")->mustTerm('category', '电子产品');
            });
```

打印结果 `var_dump($params->toArray()`:

```php
[
  "index" => "goods",
  "type" => "_doc",
  "body" => [
    "_source" => ["no", "name", "price", "category"],
    "size" => 10,
    "query" => [
      "bool" => [
        "must" => [
          [
            "match" => ["name" => "小米手机"]
          ],
          [
            "term" => [
              "category" => ["value" => "电子产品"]
            ]
          ]
        ]
      ]
    ]
  ]
];
```

将结果通过 `elasticsearch/elasticsearch` 官方扩展包执行:

```php
use Elasticsearch\ClientBuilder;

...

$client = ClientBuilder::create()->fromConfig($config);

$client->search($params);
```

### 查询

`Boyfoo\ElasticsearchSql\Query` 类为 `Elasticsearch query` 语句构建类

```php
use Boyfoo\ElasticsearchSql\Query;

$query = Query::create()
            ->mustMatch('字段1', '内容1')
            ->notTerm('字段1', '内容2')
            ->shouldRange('字段3', [
                '>=' => 2018, '<=' => 2019
            ]);
```

打印 `query` 构建器结果: `var_dump($query->toArray())`:

```php
[
  "bool" => [
    "must" => [
      [
        "match" => [
          "字段1" => "内容1"
        ]
      ]
    ],
    "must_not" => [
      [
        "term" => [
          "字段1" => [
            "value" => "内容2"
          ]
        ]
      ]
    ],
    "should" => [
      [
        "range" => [
          "字段3" => [
            "gte" => 2018,
            "lte" => 2019
          ]
        ]
      ]
    ]
  ]
];
```

将 `query` 构建器放入 `search` 构建器内:

```php
use Boyfoo\ElasticsearchSql\Search;
use Boyfoo\ElasticsearchSql\Query;

$query = Query::create()->mustTerms('key', 'value');

Search::create()->query($query);
```

## License

MIT