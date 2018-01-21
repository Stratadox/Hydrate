<?php

declare(strict_types = 1);

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
use Stratadox\Hydration\Mapper\Instruction\Call;
use Stratadox\Hydration\Mapper\Instruction\Has;
use Stratadox\Hydration\Mapper\Instruction\In;
use Stratadox\Hydration\Mapper\Mapper;
use function strlen;

$container = new Container;

$container->set('database', function () use ($container)
{
    $database = new SQLite3('../books.sqlite');
    foreach (require('Database.php') as $statement) {
        $database->exec($statement);
    }
    return $database;
});

$container->set('books', function () use ($container)
{
    return MappedHydrator::fromThis($container->get('books.mapping'));
});

$container->set('books.mapping', function () use ($container)
{
    return Mapper::forThe(Book::class)
        ->property('title', Has::one(Title::class)->with('title'))
        ->property('isbn', Has::one(Isbn::class)
            ->with('code', In::key('id'))
            ->with('version', Call::the(function ($data) {
                return strlen($data['id']);
            }))
        )
        ->property('author', Has::one(Author::class)
            ->with('firstName', In::key('author_first_name'))
            ->with('lastName', In::key('author_last_name'))
        )
        ->property('contents', Has::many(ChapterProxy::class, In::key('chapters'))
            ->containedInA(Contents::class)
            ->loadedBy(ChapterLoaderFactory::withAccessTo($container->get('database')))
        )
        ->property('format')
        ->map();
});

return $container;
