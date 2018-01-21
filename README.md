# Hydrate

[![Build Status](https://travis-ci.org/Stratadox/Hydrate.svg?branch=master)](https://travis-ci.org/Stratadox/Hydrate)

The `Hydrate` module contains all that is needed to hydrate objects from an array
of data. This data could come from a sql database, a document store, a web API 
or any other source.

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

In fact, the object graph for the above mapping would transform a query result 
like this:
```
+------------+---------------+-------------------+------------------+----------------------------+
| id         | title         | author_first_name | author_last_name | contents                   |
+------------+---------------+-------------------+------------------+----------------------------+
| 1234567890 | Book Title    | John              | Doe              | Lorem ipsum dolor sit amed |
| 9876543210 | Foo, bar, baz | Jackie            | Chan             | Lorem ipsum dolor sit amed |
+------------+---------------+-------------------+------------------+----------------------------+
```

```
array(2) {
  [0]=>
  object(Book)#1 (4) {
    ["title"]=>
    object(Title)#2 (1) {
      ["title"]=>
      string(10) "Book Title"
    }
    ["isbn"]=>
    object(Isbn)#3 (1) {
      ["code"]=>
      string(10) "1234567890"
    }
    ["author"]=>
    object(Author)#4 (2) {
      ["firstName"]=>
      string(4) "John"
      ["lastName"]=>
      string(3) "Doe"
    }
    ["contents"]=>
    string(26) "Lorem ipsum dolor sit amed"
  }
  [1]=>
  object(Book)#1 (4) {
    ["title"]=>
    object(Title)#2 (1) {
      ["title"]=>
      string(10) "Foo, bar, baz"
    }
    ["isbn"]=>
    object(Isbn)#3 (1) {
      ["code"]=>
      string(10) "9876543210"
    }
    ["author"]=>
    object(Author)#4 (2) {
      ["firstName"]=>
      string(4) "Jackie"
      ["lastName"]=>
      string(3) "Chan"
    }
    ["contents"]=>
    string(26) "Lorem ipsum dolor sit amed"
  }
}

```


## Subpackages
The `Hydrate` package contains no source code (save some integration tests)
Instead it composes several sub-packages. These packages are listed below.

### Hydrator
Classes that produce hydrated objects.

[![Source Code](https://img.shields.io/badge/source-github-brightgreen.svg)](https://github.com/Stratadox/Hydrator)
[![Build Status](https://travis-ci.org/Stratadox/Hydrator.svg?branch=master)](https://travis-ci.org/Stratadox/Hydrator)
[![Coverage Status](https://coveralls.io/repos/github/Stratadox/Hydrator/badge.svg?branch=master)](https://coveralls.io/github/Stratadox/Hydrator?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stratadox/Hydrator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Stratadox/Hydrator/?branch=master)

### Hydration mapping
Tools for mapping data structures to object properties.

[![Source Code](https://img.shields.io/badge/source-github-brightgreen.svg)](https://github.com/Stratadox/HydrationMapping)
[![Build Status](https://travis-ci.org/Stratadox/HydrationMapping.svg?branch=master)](https://travis-ci.org/Stratadox/HydrationMapping)
[![Coverage Status](https://coveralls.io/repos/github/Stratadox/HydrationMapping/badge.svg?branch=master)](https://coveralls.io/github/Stratadox/HydrationMapping?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stratadox/HydrationMapping/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Stratadox/HydrationMapping/?branch=master)

### Hydration proxies
Lazy loading through proxy objects.

[![Source Code](https://img.shields.io/badge/source-github-brightgreen.svg)](https://github.com/Stratadox/HydrationProxies)
[![Build Status](https://travis-ci.org/Stratadox/HydrationProxies.svg?branch=master)](https://travis-ci.org/Stratadox/HydrationProxies)
[![Coverage Status](https://coveralls.io/repos/github/Stratadox/HydrationProxies/badge.svg?branch=master)](https://coveralls.io/github/Stratadox/HydrationProxies?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stratadox/HydrationProxies/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Stratadox/HydrationProxies/?branch=master)

### Hydration mapper
Mapping builder for easier domain mapping.

[![Source Code](https://img.shields.io/badge/source-github-brightgreen.svg)](https://github.com/Stratadox/HydrationMapper)
[![Build Status](https://travis-ci.org/Stratadox/HydrationMapper.svg?branch=master)](https://travis-ci.org/Stratadox/HydrationMapper)
[![Coverage Status](https://coveralls.io/repos/github/Stratadox/HydrationMapper/badge.svg?branch=master)](https://coveralls.io/github/Stratadox/HydrationMapper?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stratadox/HydrationMapper/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Stratadox/HydrationMapper/?branch=master)

### Hydration 
Interfaces for the above subpackages.

[![Source Code](https://img.shields.io/badge/source-github-brightgreen.svg)](https://github.com/Stratadox/Hydration)
