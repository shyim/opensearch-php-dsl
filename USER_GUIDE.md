# OpenSearch DSL User Guide

This guide explains how to use the OpenSearch DSL library to build search queries, aggregations, and other search features for OpenSearch.

## Installation

```bash
composer require shyim/opensearch-php-dsl
```

> **Note**: This library does **not** require `elasticsearch/elasticsearch` or `opensearch-project/opensearch-php`. It is a standalone query builder that generates arrays. You can use it with any HTTP client to communicate with OpenSearch.

## Basic Usage

The core of the library is the `Search` class. You build a `Search` object, convert it to an array, and pass it to your OpenSearch client.

```php
<?php

require 'vendor/autoload.php';

use OpenSearchDSL\Search;
use OpenSearchDSL\Query\MatchAllQuery;

$search = new Search();
$search->addQuery(new MatchAllQuery());

$params = [
    'index' => 'my_index',
    'body'  => $search->toArray(),
];

// Use with opensearch-php client
$client = \OpenSearch\ClientBuilder::create()->build();
$results = $client->search($params);
```

## Building Queries

### Match All Query

```php
use OpenSearchDSL\Query\MatchAllQuery;

$search = new Search();
$search->addQuery(new MatchAllQuery());
```

Produces:
```json
{
    "query": {
        "match_all": {}
    }
}
```

### Match Query

```php
use OpenSearchDSL\Query\FullText\MatchQuery;

$search->addQuery(new MatchQuery('title', 'search text'));
```

### Multi-Match Query

```php
use OpenSearchDSL\Query\FullText\MultiMatchQuery;

$search->addQuery(new MultiMatchQuery(['title', 'description'], 'search text'));
```

### Term Query

```php
use OpenSearchDSL\Query\TermLevel\TermQuery;

$search->addQuery(new TermQuery('status', 'active'));
```

### Terms Query (multiple values)

```php
use OpenSearchDSL\Query\TermLevel\TermsQuery;

$search->addQuery(new TermsQuery('tags', ['php', 'opensearch', 'dsl']));
```

### Range Query

```php
use OpenSearchDSL\Query\TermLevel\RangeQuery;

$search->addQuery(new RangeQuery('price', [
    RangeQuery::GTE => 100,
    RangeQuery::LTE => 500,
]));
```

### Exists Query

```php
use OpenSearchDSL\Query\TermLevel\ExistsQuery;

$search->addQuery(new ExistsQuery('email'));
```

## Boolean (Compound) Queries

The `BoolQuery` allows combining multiple queries with `must`, `should`, `must_not`, and `filter` clauses.

```php
use OpenSearchDSL\Query\Compound\BoolQuery;
use OpenSearchDSL\Query\FullText\MatchQuery;
use OpenSearchDSL\Query\TermLevel\TermQuery;

$boolQuery = new BoolQuery();
$boolQuery->add(new MatchQuery('title', 'php'), BoolQuery::MUST);
$boolQuery->add(new TermQuery('status', 'published'), BoolQuery::FILTER);
$boolQuery->add(new TermQuery('category', 'tutorial'), BoolQuery::SHOULD);

$search->addQuery($boolQuery);
```

Produces:
```json
{
    "query": {
        "bool": {
            "must": [
                { "match": { "title": "php" } }
            ],
            "filter": [
                { "term": { "status": "published" } }
            ],
            "should": [
                { "term": { "category": "tutorial" } }
            ]
        }
    }
}
```

## Aggregations

### Terms Aggregation

```php
use OpenSearchDSL\Aggregation\Bucketing\TermsAggregation;

$termsAgg = new TermsAggregation('by_category', 'category');
$search->addAggregation($termsAgg);
```

### Date Histogram Aggregation

```php
use OpenSearchDSL\Aggregation\Bucketing\DateHistogramAggregation;

$dateHistogram = new DateHistogramAggregation(
    'sales_over_time',
    'date',
    'month'  // calendar interval
);
$search->addAggregation($dateHistogram);
```

### Metric Aggregations

```php
use OpenSearchDSL\Aggregation\Metric\AvgAggregation;
use OpenSearchDSL\Aggregation\Metric\SumAggregation;
use OpenSearchDSL\Aggregation\Metric\MaxAggregation;
use OpenSearchDSL\Aggregation\Metric\MinAggregation;

$search->addAggregation(new AvgAggregation('avg_price', 'price'));
$search->addAggregation(new SumAggregation('total_sales', 'amount'));
$search->addAggregation(new MaxAggregation('max_price', 'price'));
$search->addAggregation(new MinAggregation('min_price', 'price'));
```

### Nested Aggregations

```php
use OpenSearchDSL\Aggregation\Bucketing\TermsAggregation;
use OpenSearchDSL\Aggregation\Metric\AvgAggregation;

$categoryAgg = new TermsAggregation('categories', 'category');
$categoryAgg->addAggregation(new AvgAggregation('avg_price', 'price'));

$search->addAggregation($categoryAgg);
```

## Sorting

```php
use OpenSearchDSL\Sort\FieldSort;

$search->addSort(new FieldSort('created_at', FieldSort::DESC));
$search->addSort(new FieldSort('price', FieldSort::ASC, null, ['mode' => 'avg']));
```

## Pagination

```php
$search->setFrom(0);
$search->setSize(20);
```

## Source Filtering

Control which fields are returned:

```php
// Return only specific fields
$search->setSource(['title', 'author', 'created_at']);

// Exclude fields
$search->setSource(['excludes' => ['content', 'raw_data']]);

// Disable source entirely
$search->setSource(false);
```

## Highlighting

```php
use OpenSearchDSL\Highlight\Highlight;

$highlight = new Highlight();
$highlight->addField('title');
$highlight->addField('content', ['fragment_size' => 150, 'number_of_fragments' => 3]);
$highlight->setTags(['<em class="highlight">'], ['</em>']);

$search->addHighlight($highlight);
```

## Post Filters

Post filters are applied after aggregations are calculated:

```php
use OpenSearchDSL\Query\TermLevel\TermQuery;

$search->addPostFilter(new TermQuery('status', 'active'));
```

## Suggestions

```php
use OpenSearchDSL\Suggest\Suggest;

// Signature: new Suggest(string $name, string $type, string $text, string $field, array $parameters = [])
$suggest = new Suggest('my-suggestion', 'term', 'nirn', 'title', ['size' => 5]);
$search->addSuggest($suggest);
```

Produces:
```json
{
    "suggest": {
        "my-suggestion": {
            "text": "nirn",
            "term": {
                "field": "title",
                "size": 5
            }
        }
    }
}
```

## Complete Example

```php
<?php

require 'vendor/autoload.php';

use OpenSearchDSL\Search;
use OpenSearchDSL\Query\Compound\BoolQuery;
use OpenSearchDSL\Query\FullText\MatchQuery;
use OpenSearchDSL\Query\TermLevel\RangeQuery;
use OpenSearchDSL\Query\TermLevel\TermQuery;
use OpenSearchDSL\Aggregation\Bucketing\TermsAggregation;
use OpenSearchDSL\Aggregation\Metric\AvgAggregation;
use OpenSearchDSL\Sort\FieldSort;
use OpenSearchDSL\Highlight\Highlight;

// Build the search
$search = new Search();

// Main query
$boolQuery = new BoolQuery();
$boolQuery->add(new MatchQuery('title', 'opensearch tutorial'), BoolQuery::MUST);
$boolQuery->add(new RangeQuery('published_at', [
    RangeQuery::GTE => 'now-30d/d',
]), BoolQuery::FILTER);

$search->addQuery($boolQuery);

// Aggregations
$categoryAgg = new TermsAggregation('categories', 'category');
$categoryAgg->addAggregation(new AvgAggregation('avg_reading_time', 'reading_time'));
$search->addAggregation($categoryAgg);

// Sorting
$search->addSort(new FieldSort('published_at', FieldSort::DESC));

// Pagination
$search->setFrom(0);
$search->setSize(20);

// Source filtering
$search->setSource(['title', 'author', 'category', 'published_at']);

// Highlighting
$highlight = new Highlight();
$highlight->addField('title');
$highlight->addField('content');
$search->addHighlight($highlight);

// Execute
$params = [
    'index' => 'blog_posts',
    'body'  => $search->toArray(),
];

$client = \OpenSearch\ClientBuilder::create()->build();
$results = $client->search($params);
```

## Using with Different Clients

Since this library only builds query arrays, you can use it with any OpenSearch client:

### With opensearch-php

```php
$client = \OpenSearch\ClientBuilder::create()->build();
$results = $client->search([
    'index' => 'my_index',
    'body' => $search->toArray(),
]);
```

### With elasticsearch-php

```php
$client = \Elastic\Elasticsearch\ClientBuilder::create()->build();
$results = $client->search([
    'index' => 'my_index',
    'body' => $search->toArray(),
]);
```

### With raw HTTP client

```php
$client = new \GuzzleHttp\Client();
$response = $client->post('http://localhost:9200/my_index/_search', [
    'json' => $search->toArray(),
]);
$results = json_decode($response->getBody(), true);
```

## Available Query Types

| Category | Classes |
|----------|---------|
| **Compound** | `BoolQuery`, `BoostingQuery`, `ConstantScoreQuery`, `DisMaxQuery`, `FunctionScoreQuery` |
| **Full Text** | `MatchQuery`, `MatchPhraseQuery`, `MatchPhrasePrefixQuery`, `MultiMatchQuery`, `CommonTermsQuery`, `QueryStringQuery`, `SimpleQueryStringQuery` |
| **Term Level** | `TermQuery`, `TermsQuery`, `TermsSetQuery`, `RangeQuery`, `ExistsQuery`, `PrefixQuery`, `WildcardQuery`, `RegexpQuery`, `FuzzyQuery`, `IdsQuery` |
| **Geo** | `GeoBoundingBoxQuery`, `GeoDistanceQuery`, `GeoPolygonQuery`, `GeoShapeQuery` |
| **Joining** | `NestedQuery`, `HasChildQuery`, `HasParentQuery`, `ParentIdQuery` |
| **Specialized** | `MoreLikeThisQuery`, `ScriptQuery` |
| **Span** | `SpanTermQuery`, `SpanNearQuery`, `SpanOrQuery`, `SpanNotQuery`, `SpanFirstQuery`, `SpanContainingQuery`, `SpanWithinQuery`, `SpanMultiTermQuery` |

## Available Aggregation Types

| Category | Classes |
|----------|---------|
| **Bucketing** | `TermsAggregation`, `DateHistogramAggregation`, `HistogramAggregation`, `RangeAggregation`, `DateRangeAggregation`, `FilterAggregation`, `FiltersAggregation`, `NestedAggregation`, `ReverseNestedAggregation`, `GlobalAggregation`, `MissingAggregation`, `GeoDistanceAggregation`, `GeoHashGridAggregation`, `CompositeAggregation`, `SamplerAggregation`, `DiversifiedSamplerAggregation`, `SignificantTermsAggregation`, `SignificantTextAggregation`, `AdjacencyMatrixAggregation`, `AutoDateHistogramAggregation`, `ChildrenAggregation`, `Ipv4RangeAggregation` |
| **Metric** | `AvgAggregation`, `SumAggregation`, `MinAggregation`, `MaxAggregation`, `StatsAggregation`, `ExtendedStatsAggregation`, `ValueCountAggregation`, `CardinalityAggregation`, `PercentilesAggregation`, `PercentileRanksAggregation`, `GeoBoundsAggregation`, `GeoCentroidAggregation`, `ScriptedMetricAggregation`, `TopHitsAggregation` |
| **Pipeline** | `AvgBucketAggregation`, `SumBucketAggregation`, `MinBucketAggregation`, `MaxBucketAggregation`, `StatsBucketAggregation`, `ExtendedStatsBucketAggregation`, `PercentilesBucketAggregation`, `DerivativeAggregation`, `CumulativeSumAggregation`, `MovingAverageAggregation`, `MovingFunctionAggregation`, `SerialDifferencingAggregation`, `BucketScriptAggregation`, `BucketSelectorAggregation`, `BucketSortAggregation` |

## Additional Resources

- [OpenSearch Documentation](https://opensearch.org/docs/latest/)
- [Query DSL Reference](https://opensearch.org/docs/latest/query-dsl/)
- [Aggregations Reference](https://opensearch.org/docs/latest/aggregations/)
