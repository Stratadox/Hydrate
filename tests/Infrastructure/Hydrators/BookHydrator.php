<?php

declare(strict_types=1);

namespace Stratadox\Hydrate\Test\Infrastructure\Hydrators;

use SQLite3;
use Stratadox\Hydrate\Test\Infrastructure\Loaders\ChapterLoaderFactory;
use Stratadox\Hydrate\Test\Model\Book;
use Stratadox\Hydrate\Test\Model\ChapterProxy;
use Stratadox\Hydrate\Test\Model\Contents;
use Stratadox\Hydration\Hydrator\MappedHydrator;
use Stratadox\Hydration\Hydrator\SimpleHydrator;
use Stratadox\Hydration\Hydrator\VariadicConstructor;
use Stratadox\Hydration\Mapping\Mapping;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneEmbedded;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\Hydration\Proxying\AlterableCollectionEntryUpdaterFactory;
use Stratadox\Hydration\Proxying\ProxyFactory;

class BookHydrator extends Decorator
{
    public static function withAccessTo(SQLite3 $database)
    {
        return new static(
            MappedHydrator::fromThis(Mapping::ofThe(Book::class,
                HasOneEmbedded::inProperty('title', TitleHydrator::create()),
                HasOneEmbedded::inProperty('isbn', IsbnHydrator::create()),
                HasOneEmbedded::inProperty('author', AuthorHydrator::create()),
                HasManyProxies::inPropertyWithDifferentKey('contents', 'chapters',
                    VariadicConstructor::forThe(Contents::class),
                    ProxyFactory::fromThis(
                        SimpleHydrator::forThe(ChapterProxy::class),
                        ChapterLoaderFactory::withAccessTo($database),
                        new AlterableCollectionEntryUpdaterFactory
                    )
                ),
                StringValue::inProperty('format')
            ))
        );
    }
}
