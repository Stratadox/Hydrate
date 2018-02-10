# Hydrate

[![Build Status](https://travis-ci.org/Stratadox/Hydrate.svg?branch=master)](https://travis-ci.org/Stratadox/Hydrate)

The `Hydrate` module contains all that is needed to hydrate objects from 
arbitrary data. This data could come from a sql database, a document store, a 
web API or any other source.

## Installation

Install using composer:

`composer require stratadox/hydrate`

## Basic Usage

1. Use the Mapper to create a hydrator that can can build your domain model from
the input data:

```php
$hydrator = Mapper::forThe(Book::class)
   ->property('title', Has::one(Title::class)->with('title'))
   ->property('isbn', Has::one(Isbn::class)->with('code', In::key('id'))
   ->property('author', Has::one(Author::class)
       ->with('firstName', In::key('author_first_name'))
       ->with('lastName', In::key('author_last_name'))
   )
   ->property('contents')
   ->hydrator();
```

2. Load the data from the data source:
```php
$books = [];
$result = $database->query("SELECT * FROM `book`");
while ($data = $result->row) {
    $books[] = $hydrator->fromArray($data);
}
```

3. All done! Your objects, in this case a bunch of books, are now hydrated as 
fully fledged object graphs, each containing their related objects, like a 
Title, an Isbn and an Author.

In fact, the above mapping would transform a query result like this:
```
+------------+---------------+-------------------+------------------+----------------------------+
| id         | title         | author_first_name | author_last_name | contents                   |
+------------+---------------+-------------------+------------------+----------------------------+
| 1234567890 | Book Title    | John              | Doe              | Lorem ipsum dolor sit amed |
| 9876543210 | Foo, bar, baz | Jackie            | Chan             | Lorem ipsum dolor sit amed |
+------------+---------------+-------------------+------------------+----------------------------+
```

...into an object structure like this:

```
array(2) {
  [0] => object(Book)#1 (4) {
    ["title"] => object(Title)#2 (1) {
      ["title"] => string(10) "Book Title"
    }
    ["isbn"] => object(Isbn)#3 (1) {
      ["code"] => string(10) "1234567890"
    }
    ["author"] => object(Author)#4 (2) {
      ["firstName"] => string(4) "John"
      ["lastName"] => string(3) "Doe"
    }
    ["contents"] => string(26) "Lorem ipsum dolor sit amed"
  }
  [1] => object(Book)#1 (4) {
    ["title"] => object(Title)#2 (1) {
      ["title"] => string(10) "Foo, bar, baz"
    }
    ["isbn"] => object(Isbn)#3 (1) {
      ["code"] => string(10) "9876543210"
    }
    ["author"] => object(Author)#4 (2) {
      ["firstName"] => string(4) "Jackie"
      ["lastName"] => string(3) "Chan"
    }
    ["contents"] => string(26) "Lorem ipsum dolor sit amed"
  }
}

```

## Features

The example above is only the top of the iceberg of possibilities. The `Hydrate` 
package provides great (and often unique) features, such as:
* Producing value objects using multiple keys of the result set
* Lazy (and extra lazy) loading relationships, even if they're in arrays or immutable collections!
* Deciding on a concrete implementation at runtime ("single table inheritance")
* Complete control over data interpretation by calling a Closure to load the 
property value.
* [...and more!](documentation/README.md)

## Subpackages
The `Hydrate` package contains no source code (save some integration tests)
Instead it composes several sub-packages. These packages are listed below.

### Hydrator
Produces objects, complete with data in their properties, without calling constructor functions.

[![Source Code](https://img.shields.io/badge/source-github-blue.svg)](https://github.com/Stratadox/Hydrator)
[![Implements](https://img.shields.io/badge/interfaces-github-blue.svg)](https://github.com/Stratadox/HydratorContracts)
[![Build Status](https://travis-ci.org/Stratadox/Hydrator.svg?branch=master)](https://travis-ci.org/Stratadox/Hydrator)
[![Coverage Status](https://coveralls.io/repos/github/Stratadox/Hydrator/badge.svg?branch=master)](https://coveralls.io/github/Stratadox/Hydrator?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stratadox/Hydrator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Stratadox/Hydrator/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/stratadox/hydrator/v/stable)](https://packagist.org/packages/stratadox/hydrator)
[![License](https://poser.pugx.org/stratadox/hydrator/license)](https://packagist.org/packages/stratadox/hydrator)

### Hydration mapping
Provides instructions to the hydrators on how to map the input data to the object properties.

[![Source Code](https://img.shields.io/badge/source-github-blue.svg)](https://github.com/Stratadox/HydrationMapping)
[![Implements](https://img.shields.io/badge/interfaces-github-blue.svg)](https://github.com/Stratadox/HydrationMappingContracts)
[![Build Status](https://travis-ci.org/Stratadox/HydrationMapping.svg?branch=master)](https://travis-ci.org/Stratadox/HydrationMapping)
[![Coverage Status](https://coveralls.io/repos/github/Stratadox/HydrationMapping/badge.svg?branch=master)](https://coveralls.io/github/Stratadox/HydrationMapping?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stratadox/HydrationMapping/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Stratadox/HydrationMapping/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/stratadox/hydration-mapping/v/stable)](https://packagist.org/packages/stratadox/hydration-mapping)
[![License](https://poser.pugx.org/stratadox/hydration-mapping/license)](https://packagist.org/packages/stratadox/hydration-mapping)

### Proxy
Provides proxy functionality for lazy loading of objects whose data is not loaded into the system yet.

[![Source Code](https://img.shields.io/badge/source-github-blue.svg)](https://github.com/Stratadox/Proxy)
[![Implements](https://img.shields.io/badge/interfaces-github-blue.svg)](https://github.com/Stratadox/ProxyContracts)
[![Build Status](https://travis-ci.org/Stratadox/Proxy.svg?branch=master)](https://travis-ci.org/Stratadox/Proxy)
[![Coverage Status](https://coveralls.io/repos/github/Stratadox/Proxy/badge.svg?branch=master)](https://coveralls.io/github/Stratadox/Proxy?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stratadox/Proxy/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Stratadox/Proxy/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/stratadox/proxy/v/stable)](https://packagist.org/packages/stratadox/proxy)
[![License](https://poser.pugx.org/stratadox/proxy/license)](https://packagist.org/packages/stratadox/proxy)

### Hydration mapper
Tools to ease the construction of hydration maps.

[![Source Code](https://img.shields.io/badge/source-github-blue.svg)](https://github.com/Stratadox/HydrationMapper)
[![Implements](https://img.shields.io/badge/interfaces-github-blue.svg)](https://github.com/Stratadox/HydrationMapperContracts)
[![Build Status](https://travis-ci.org/Stratadox/HydrationMapper.svg?branch=master)](https://travis-ci.org/Stratadox/HydrationMapper)
[![Coverage Status](https://coveralls.io/repos/github/Stratadox/HydrationMapper/badge.svg?branch=master)](https://coveralls.io/github/Stratadox/HydrationMapper?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stratadox/HydrationMapper/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Stratadox/HydrationMapper/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/stratadox/hydration-mapper/v/stable)](https://packagist.org/packages/stratadox/hydration-mapper)
[![License](https://poser.pugx.org/stratadox/hydration-mapper/license)](https://packagist.org/packages/stratadox/hydration-mapper)
