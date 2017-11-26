<?php

declare(strict_types=1);

namespace Stratadox\Hydrate\Test\Infrastructure\Loaders;

use SQLite3;
use SQLite3Result;
use Stratadox\Hydrate\Test\Model\Book;
use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Proxying\Loader;

class TitleLoader extends Loader
{
    private $database;
    private $hydrateTheTitle;

    public function __construct(
        SQLite3 $database,
        Hydrates $hydrateTheTitle,
        Book $book,
        int $chapter
    ) {
        $this->database = $database;
        $this->hydrateTheTitle = $hydrateTheTitle;
        parent::__construct($book, '', $chapter);
    }

    protected function doLoad($book, string $property, $chapter = null)
    {
        $result = $this->fetchTheTitleOfTheChapterInThe($book, $chapter);
        $title = $this->hydrateTheTitle->fromArray(
            $result->fetchArray(SQLITE3_ASSOC)
        );
        return $title;
    }

    private function fetchTheTitleOfTheChapterInThe(Book $toRead, int $chapter = null) : SQLite3Result
    {
        $query = $this->database->prepare(
            'SELECT `chapter`.`title` as `title`
                FROM `content` JOIN `chapter` ON (
                    `content`.`chapter_id` = `chapter`.`id` AND
                    `content`.`book_id` = :book AND
                    `content`.`chapter_number` = :chapter
                );'
        );
        $query->bindValue('book', $toRead->isbn());
        $query->bindValue('chapter', $chapter);
        return $query->execute();
    }
}
