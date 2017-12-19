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

$container = new Container;

$container->set('database', function () use ($container) {
    $database = new SQLite3('../books.sqlite');
    foreach (require('Database.php') as $statement) {
        $database->exec($statement);
    }
    return $database;
});

$container->set('books', function () use ($container) {
    return MappedHydrator::fromThis(Mapping::ofThe(Book::class,
        HasOneEmbedded::inProperty('title', $container->get('title')),
        HasOneEmbedded::inProperty('isbn', $container->get('isbn')),
        HasOneEmbedded::inProperty('author', $container->get('author')),
        HasManyProxies::inPropertyWithDifferentKey('contents', 'chapters',
            VariadicConstructor::forThe(Contents::class),
            $container->get('chapterProxies')
        ),
        StringValue::inProperty('format')
    ));
});

$container->set('title', function () {
    return MappedHydrator::fromThis(Mapping::ofThe(Title::class,
        StringValue::inProperty('title')
    ));
});

$container->set('isbn', function () {
    return MappedHydrator::fromThis(Mapping::ofThe(Isbn::class,
        StringValue::inPropertyWithDifferentKey('code', 'id')
    ));
});

$container->set('author', function () {
    return MappedHydrator::fromThis(Mapping::ofThe(Author::class,
        StringValue::inPropertyWithDifferentKey('firstName', 'author_first_name'),
        StringValue::inPropertyWithDifferentKey('lastName', 'author_last_name')
    ));
});

$container->set('chapterProxies', function () use ($container) {
    return ProxyFactory::fromThis(
        SimpleHydrator::forThe(ChapterProxy::class),
        ChapterLoaderFactory::withAccessTo($container->get('database')),
        new AlterableCollectionEntryUpdaterFactory
    );
});

return $container;
