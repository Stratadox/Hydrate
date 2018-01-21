<?php

declare(strict_types = 1);

namespace Stratadox\Hydrate\Test\Infrastructure\Loaders;

use SQLite3;
use Stratadox\Hydrate\Test\Model\Book;
use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\LoadsProxiedObjects;
use Stratadox\Hydration\ProducesProxyLoaders;

class ChapterLoaderFactory implements ProducesProxyLoaders
{
    private $database;
    private $hydrator;

    public function __construct(
        SQLite3 $database,
        Hydrates $chapter
    ) {
        $this->database = $database;
        $this->hydrator = $chapter;
    }

    public static function withAccessTo(
        SQLite3 $database,
        Hydrates $hydrator
    ) : ProducesProxyLoaders
    {
        return new static($database, $hydrator);
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
            $this->hydrator,
            $book,
            $chapter
        );
    }
}
