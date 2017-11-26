<?php

declare(strict_types=1);

namespace Stratadox\Hydrate\Test\Infrastructure;

use SQLite3;
use Stratadox\Hydrate\Test\Infrastructure\Loaders\ChapterLoaderFactory;
use Stratadox\Hydrate\Test\Infrastructure\Loaders\TitleLoaderFactory;
use Stratadox\Hydrate\Test\Model\Chapter;
use Stratadox\Hydrate\Test\Model\Text;
use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Hydrator\MappedHydrator;
use Stratadox\Hydration\Hydrator\SimpleHydrator;
use Stratadox\Hydration\Hydrator\VariadicConstructor;
use Stratadox\Hydration\Mapping\Mapping;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneEmbedded;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\Hydrate\Test\Model\Author;
use Stratadox\Hydrate\Test\Model\Book;
use Stratadox\Hydrate\Test\Model\ChapterProxy;
use Stratadox\Hydrate\Test\Model\Contents;
use Stratadox\Hydrate\Test\Model\Isbn;
use Stratadox\Hydrate\Test\Model\Title;
use Stratadox\Hydration\Proxying\AlterableCollectionEntryUpdaterFactory;
use Stratadox\Hydration\Proxying\ProxyFactory;

class BookHydrator implements Hydrates
{
    private $hydrator;

    public function __construct(Hydrates $wrappedHydrator)
    {
        $this->hydrator = $wrappedHydrator;
    }

    public static function withAccessTo(SQLite3 $database)
    {
        return new static(
            MappedHydrator::fromThis(Mapping::ofThe(Book::class,
                HasOneEmbedded::inProperty('title',
                    MappedHydrator::fromThis(Mapping::ofThe(Title::class,
                        StringValue::inProperty('title')
                    ))
                ),
                HasOneEmbedded::inProperty('isbn',
                    MappedHydrator::fromThis(Mapping::ofThe(Isbn::class,
                        StringValue::inPropertyWithDifferentKey('code', 'id')
                    ))
                ),
                HasOneEmbedded::inProperty('author',
                    MappedHydrator::fromThis(Mapping::ofThe(Author::class,
                        StringValue::inPropertyWithDifferentKey('firstName', 'author_first_name'),
                        StringValue::inPropertyWithDifferentKey('lastName', 'author_last_name')
                    ))
                ),
                StringValue::inProperty('format'),
                HasManyProxies::inPropertyWithDifferentKey('contents', 'chapters',
                    VariadicConstructor::forThe(Contents::class),
                    ProxyFactory::fromThis(
                        SimpleHydrator::forThe(ChapterProxy::class),
                        new ChapterLoaderFactory(
                            $database,
                            SimpleHydrator::forThe(Text::class),
                            VariadicConstructor::forThe(Chapter::class),
                            new TitleLoaderFactory(
                                $database,
                                SimpleHydrator::forThe(Title::class)
                            )
                        ),
                        new AlterableCollectionEntryUpdaterFactory
                    )
                )
            ))
        );
    }

    public function fromArray(array $input)
    {
        return $this->hydrator->fromArray($input);
    }
}
