<?php

declare(strict_types=1);

namespace Stratadox\Hydrate;

use PHPUnit\Framework\TestCase;
use SQLite3;
use Stratadox\Hydrate\Test\Infrastructure\BookHydrator;
use Stratadox\Hydration\Hydrates;
use Stratadox\Hydrate\Test\Model\Author;
use Stratadox\Hydrate\Test\Model\Book;
use Stratadox\Hydrate\Test\Model\Contents;
use Stratadox\Hydrate\Test\Model\Isbn;
use Stratadox\Hydrate\Test\Model\Title;

class I_want_to_hydrate_database_records extends TestCase
{
    const DATABASE_FILE = 'tests/books.sqlite';

    /** @var SQLite3 */
    private $database;

    /** @var  Hydrates */
    private $books;

    /** @scenario */
    function hydrating_a_query_result_into_an_object_structure()
    {
        $result = $this->database->query("SELECT * FROM `book`");
        /** @var Book[] $books */
        $books = [];
        while ($data = $result->fetchArray(SQLITE3_ASSOC)) {
            $books[] = $this->books->fromArray($data);
        }

        $this->assertNotEmpty($books);
        foreach ($books as $book) {
            $this->assertInstanceOf(Book::class, $book);
            $this->assertInstanceOf(Isbn::class, $book->isbn());
            $this->assertInstanceOf(Title::class, $book->title());
            $this->assertInstanceOf(Author::class, $book->author());
            $this->assertInstanceOf(Contents::class, $book->contents());
        }
    }

    /** @scenario */
    function accessing_data_that_was_not_in_the_original_result_set()
    {
        /** @var Book $book */
        $book = $this->books->fromArray($this->selectBookDataByIsbn('9781493634149'));

        $this->assertEquals(
            Author::named('Elle', 'Garner'),
            $book->author()
        );
        $this->assertEquals(
            new Title('Fruit Infused Water: 50 Quick & Easy Recipes for Delicious & Healthy Hydration'),
            $book->title()
        );
        $this->assertEquals(
            "Content not available:\nPurchase book for actual content...",
            $book->textInChapter(1)
        );
    }

    /** @scenario */
    function changing_database_values_before_lazily_loading_the_data_affects_the_loaded_object()
    {
        $book = $this->books->fromArray($this->selectBookDataByIsbn('9781493634149'));

        $randomContent = 'Random content: ' . microtime() .' / '. rand();

        $this->insertTextIntoTheDatabase($randomContent);

        $this->assertEquals(
            "Purchase book for actual content of chapter 2...\n$randomContent",
            $book->textInChapter(2)
        );
    }

    /** @scenario */
    function changing_database_values_after_lazily_loading_the_data_does_not_affect_the_loaded_object()
    {
        $book = $this->books->fromArray($this->selectBookDataByIsbn('9781493634149'));

        $book->textInChapter(2);

        $randomContent = 'Random content: ' . microtime() .' / '. rand();

        $this->insertTextIntoTheDatabase($randomContent);

        $this->assertEquals(
            "Purchase book for actual content of chapter 2...",
            $book->textInChapter(2)
        );
    }

    private function selectBookDataByIsbn(string $isbn)
    {
        $query = $this->database->prepare(
            "SELECT * FROM `book` WHERE `id` = :isbn"
        );
        $query->bindValue('isbn', $isbn);
        return $query->execute()->fetchArray(SQLITE3_ASSOC);
    }

    private function insertTextIntoTheDatabase(string $text)
    {
        $query = $this->database->prepare(
            "INSERT INTO `text` VALUES (
              1, 1, :content
            );"
        );
        $query->bindValue('content', $text);
        $query->execute();
    }

    protected function setUp() : void
    {
        $this->database = new SQLite3(static::DATABASE_FILE);
        foreach (require('Infrastructure/Database.php') as $statement) {
            $this->database->exec($statement);
        }
        $this->books = BookHydrator::withAccessTo($this->database);
    }
}
