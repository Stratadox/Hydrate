<?php

declare(strict_types=1);

namespace Stratadox\Hydrate\Test\Book\Infrastructure;

use InvalidArgumentException;
use SQLite3;
use SQLite3Result;
use Stratadox\Hydrate\Test\Book\Book;
use Stratadox\Hydrator\Hydrates;
use Stratadox\Proxy\Loader;

class ChapterLoader extends Loader
{
    private $database;
    private $hydrator;

    public function __construct(
        SQLite3 $database,
        Hydrates $hydrator,
        Book $book,
        int $chapter
    ) {
        $this->database = $database;
        $this->hydrator = $hydrator;
        parent::__construct($book, '', $chapter);
    }

    protected function doLoad($book, string $property, $forTheChapter = null)
    {
        if (!isset($forTheChapter)) {
            throw new InvalidArgumentException('No chapter provided.');
        }
        $forTheChapter = (int) $forTheChapter;
        return $this->hydrator->fromArray([
            'title'    => $this->titleOfTheBook($book, $forTheChapter)[0]['title'],
            'elements' => $this->elementsInThe($book, $forTheChapter)
        ]);
    }

    private function elementsInThe(Book $toRead, int $chapter): array
    {
        $query = $this->database->prepare(
            'SELECT 
              element.type as type, 
              element.text as text, 
              element.src as src, 
              element.alt as alt 
            FROM element JOIN content ON (
              content.chapter_id = element.chapter_id AND 
              content.book_id = :book AND 
              content.chapter_number = :chapter
            );'
        );
        $query->bindValue('book', $toRead->isbn());
        $query->bindValue('chapter', $chapter);
        return $this->fetchResults($query->execute());
    }

    private function titleOfTheBook(Book $toRead, int $chapter): array
    {
        $query = $this->database->prepare(
            'SELECT chapter.title as title
                FROM chapter JOIN content ON (
                    content.chapter_id = chapter.id AND
                    content.book_id = :book AND
                    content.chapter_number = :chapter
                );'
        );
        $query->bindValue('book', $toRead->isbn());
        $query->bindValue('chapter', $chapter);
        return $this->fetchResults($query->execute());
    }

    private function fetchResults(SQLite3Result $result): array
    {
        $contents = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $contents[] = $row;
        }
        return $contents;
    }
}
