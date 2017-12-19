<?php

declare(strict_types = 1);

namespace Stratadox\Hydrate\Test\Infrastructure\Loaders;

use SQLite3;
use Stratadox\Hydrate\Test\Model\Book;
use Stratadox\Hydrate\Test\Model\Chapter;
use Stratadox\Hydrate\Test\Model\Text;
use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Hydrator\SimpleHydrator;
use Stratadox\Hydration\Hydrator\VariadicConstructor;
use Stratadox\Hydration\LoadsProxiedObjects;
use Stratadox\Hydration\ProducesProxyLoaders;

class ChapterLoaderFactory implements ProducesProxyLoaders
{
    private $database;
    private $hydrateTheText;
    private $hydrateTheChapter;
    private $produceTitleLoader;

    public function __construct(
        SQLite3 $database,
        Hydrates $text,
        Hydrates $chapter,
        ProducesProxyLoaders $produceTitleLoaders
    ) {
        $this->database = $database;
        $this->hydrateTheText = $text;
        $this->hydrateTheChapter = $chapter;
        $this->produceTitleLoader = $produceTitleLoaders;
    }

    public static function withAccessTo(SQLite3 $database)
    {
        return new static(
            $database,
            SimpleHydrator::forThe(Text::class),
            VariadicConstructor::forThe(Chapter::class),
            TitleLoaderFactory::withAccessTo($database)
        );
    }

    public function makeLoaderFor(
        $theBook,
        string $ofTheProperty,
        $chapter = null
    ) : LoadsProxiedObjects
    {
        return $this->createLoaderFor($theBook, $chapter);
    }

    private function createLoaderFor(
        Book $book,
        int $chapter = null
    ) : ChapterLoader
    {
        return new ChapterLoader(
            $this->database,
            $this->produceTitleLoader->makeLoaderFor($book, '', $chapter),
            $this->hydrateTheText,
            $this->hydrateTheChapter,
            $book,
            $chapter
        );
    }
}
