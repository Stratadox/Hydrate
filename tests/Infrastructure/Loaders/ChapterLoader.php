<?php

declare(strict_types=1);

namespace Stratadox\Hydrate\Test\Infrastructure\Loaders;

use SQLite3;
use SQLite3Result;
use Stratadox\Hydrate\Test\Model\Book;
use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\LoadsProxiedObjects;
use Stratadox\Hydration\Proxying\Loader;

class ChapterLoader extends Loader
{
    private $database;
    private $titleLoader;
    private $hydrateTheText;
    private $hydrateTheChapter;

    public function __construct(
        SQLite3 $database,
        LoadsProxiedObjects $titleLoader,
        Hydrates $text,
        Hydrates $chapters,
        Book $book,
        int $chapter
    ) {
        $this->database = $database;
        $this->titleLoader = $titleLoader;
        $this->hydrateTheText = $text;
        $this->hydrateTheChapter = $chapters;
        parent::__construct($book, '', $chapter);
    }

    protected function doLoad($book, string $property, $forTheChapter = null)
    {
        $result = $this->fetchTheContentsInThe($book, $forTheChapter);
        $elements = [$this->titleLoader->loadTheInstance()];
        while ($data = $result->fetchArray(SQLITE3_ASSOC)) {
            $elements[] = $this->hydrateTheText->fromArray($data);
        }
        return $this->hydrateTheChapter->fromArray($elements);
    }

    private function fetchTheContentsInThe(Book $toRead, int $chapter = null) : SQLite3Result
    {
        $query = $this->database->prepare(
            'SELECT `text`.`contents` as `text`
            FROM `content` JOIN `text` ON (
              `content`.`chapter_id` = `text`.`chapter_id` AND 
              `content`.`book_id` = :book AND 
              `content`.`chapter_number` = :chapter
            );'
        );
        $query->bindValue('book', $toRead->isbn());
        $query->bindValue('chapter', $chapter);
        return $query->execute();
    }
}
