<?php

declare(strict_types=1);

namespace Stratadox\Hydrate\Test\Infrastructure;

use SQLite3;
use Stratadox\Di\Container;
use Stratadox\Hydrate\Test\Infrastructure\Loaders\ChapterLoaderFactory;
use Stratadox\Hydrate\Test\Model\Author;
use Stratadox\Hydrate\Test\Model\Book;
use Stratadox\Hydrate\Test\Model\ChapterProxy;
use Stratadox\Hydrate\Test\Model\Contents;
use Stratadox\Hydrate\Test\Model\Isbn;
use Stratadox\Hydrate\Test\Model\Title;
use Stratadox\Hydration\Hydrator\MappedHydrator;
use Stratadox\Hydration\Hydrator\SimpleHydrator;
use Stratadox\Hydration\Hydrator\VariadicConstructor;
use Stratadox\Hydration\Mapping\Mapping;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneEmbedded;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\Hydration\Proxying\AlterableCollectionEntryUpdaterFactory;
use Stratadox\Hydration\Proxying\ProxyFactory;

$di = new Container;

$di->set('database', function () use ($di) {
    $database = new SQLite3('../books.sqlite');
    foreach (require('Database.php') as $statement) {
        $database->exec($statement);
    }
    return $database;
});

$di->set('make.book', function () use ($di) {
    return MappedHydrator::fromThis(Mapping::ofThe(Book::class,
        HasOneEmbedded::inProperty('title', $di->get('make.title')),
        HasOneEmbedded::inProperty('isbn', $di->get('make.isbn')),
        HasOneEmbedded::inProperty('author', $di->get('make.author')),
        HasManyProxies::inPropertyWithDifferentKey('contents', 'chapters',
            VariadicConstructor::forThe(Contents::class),
            $di->get('make.chapter.proxies')
        ),
        StringValue::inProperty('format')
    ));
});

$di->set('make.title', function () {
    return MappedHydrator::fromThis(Mapping::ofThe(Title::class,
        StringValue::inProperty('title')
    ));
});

$di->set('make.isbn', function () {
    return MappedHydrator::fromThis(Mapping::ofThe(Isbn::class,
        StringValue::inPropertyWithDifferentKey('code', 'id')
    ));
});

$di->set('make.author', function () {
    return MappedHydrator::fromThis(Mapping::ofThe(Author::class,
        StringValue::inPropertyWithDifferentKey('firstName', 'author_first_name'),
        StringValue::inPropertyWithDifferentKey('lastName', 'author_last_name')
    ));
});

$di->set('make.chapter.proxies', function () use ($di) {
    return ProxyFactory::fromThis(
        SimpleHydrator::forThe(ChapterProxy::class),
        ChapterLoaderFactory::withAccessTo($di->get('database')),
        new AlterableCollectionEntryUpdaterFactory
    );
});

return $di;
