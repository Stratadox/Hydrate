<?php

declare(strict_types=1);

namespace Stratadox\Hydrate\Test\Infrastructure\Loaders;

use SQLite3;
use Stratadox\Hydrate\Test\Infrastructure\Loaders\TitleLoader;
use Stratadox\Hydrate\Test\Model\Book;
use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\LoadsProxiedObjects;
use Stratadox\Hydration\ProducesProxyLoaders;

class TitleLoaderFactory implements ProducesProxyLoaders
{
    private $database;
    private $hydrateTheTitle;

    public function __construct(
        SQLite3 $database,
        Hydrates $title
    ) {
        $this->database = $database;
        $this->hydrateTheTitle = $title;
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
    ) : TitleLoader
    {
        return new TitleLoader(
            $this->database,
            $this->hydrateTheTitle,
            $book,
            $chapter
        );
    }
}
