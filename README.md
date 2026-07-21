# OpenSearch DSL

Introducing OpenSearch DSL library to provide objective query builder for [opensearch-php](https://github.com/opensearch-project/opensearch-php) client. You can easily build any Opensearch query and transform it to an array.

This is a fork of `ongr-io/ElasticsearchDSL`, which will be more regularly updated. Thanks for ongr-io for building this Library!

If you need any help, [Github issues](https://github.com/shyim/opensearch-php-dsl/issues) is the preferred and recommended way to ask support questions.

[![Test](https://github.com/shyim/opensearch-php-dsl/actions/workflows/test.yml/badge.svg)](https://github.com/shyim/opensearch-php-dsl/actions/workflows/test.yml)
[![codecov](https://codecov.io/gh/shyim/opensearch-php-dsl/branch/main/graph/badge.svg)](https://codecov.io/gh/shyim/opensearch-php-dsl)
[![Latest Stable Version](https://poser.pugx.org/shyim/opensearch-php-dsl/v/stable)](https://packagist.org/packages/shyim/opensearch-php-dsl)
[![Total Downloads](https://poser.pugx.org/shyim/opensearch-php-dsl/downloads)](https://packagist.org/packages/shyim/opensearch-php-dsl)

## Version matrix

| OpenSearch version | OpenSearchDSL version |
|--------------------|-----------------------|
| >= 1.0             | >= 1.0                |
| >= 2.0             | >= 1.0                |

## Documentation

- **[User Guide](USER_GUIDE.md)** - Comprehensive guide with examples for building queries, aggregations, sorting, and more
- [API Documentation](docs/index.md) - Detailed reference documentation

## Quick Start

### Installation

Install library with [composer](https://getcomposer.org):

```bash
composer require shyim/opensearch-php-dsl
```

> **Note**: This library does **not** require `elasticsearch/elasticsearch` or `opensearch-project/opensearch-php`. It is a standalone query builder that generates arrays compatible with any OpenSearch client.

### Basic Example

```php
<?php

require 'vendor/autoload.php';

use OpenSearchDSL\Search;
use OpenSearchDSL\Query\MatchAllQuery;

$search = new Search();
$search->addQuery(new MatchAllQuery());

$params = [
    'index' => 'your_index',
    'body'  => $search->toArray(),
];

// Use with opensearch-php client
$client = \OpenSearch\ClientBuilder::create()->build();
$results = $client->search($params);
```

## Features

- **Query Building**: Support for all OpenSearch query types (match, term, range, bool, geo, etc.)
- **Aggregations**: Bucket, metric, and pipeline aggregations
- **Sorting**: Field sorting with multiple criteria
- **Highlighting**: Search result highlighting
- **Suggestions**: Term, phrase, and completion suggesters
- **Framework Agnostic**: Works with any HTTP client or OpenSearch client library

For detailed examples and usage instructions, see the **[User Guide](USER_GUIDE.md)**.
