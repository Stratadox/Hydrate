<?php

declare(strict_types = 1);

namespace Stratadox\Hydrate\Test;

use PHPUnit\Framework\TestCase;
use SQLite3;
use Stratadox\Hydrate\Test\Infrastructure\Loaders\ChapterLoaderFactory;
use Stratadox\Hydrate\Test\Model\Chapter;
use Stratadox\Hydrate\Test\Model\ChapterProxy;
use Stratadox\Hydrate\Test\Model\Elements;
use Stratadox\Hydrate\Test\Model\Text;
use Stratadox\Hydration\Hydrates;
use Stratadox\Hydrate\Test\Model\Author;
use Stratadox\Hydrate\Test\Model\Book;
use Stratadox\Hydrate\Test\Model\Chapters;
use Stratadox\Hydrate\Test\Model\Isbn;
use Stratadox\Hydrate\Test\Model\Title;
use Stratadox\Hydration\Hydrator\MappedHydrator;
use Stratadox\Hydration\Mapper\Instruction\Call;
use Stratadox\Hydration\Mapper\Instruction\Has;
use Stratadox\Hydration\Mapper\Instruction\In;
use Stratadox\Hydration\Mapper\Mapper;

/**
 * @coversNothing
 */
class Hydrating_database_records extends TestCase
{
    /** @var SQLite3 */
    private $database;

    /** @var Hydrates */
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
            $this->assertInstanceOf(Chapters::class, $book->chapters());
        }
    }

    /** @scenario */
    function accessing_data_that_was_not_in_the_original_result_set()
    {
        $book = $this->bookByIsbn('9781493634149');

        $this->assertEquals(
            Author::named('Elle', 'Garner'),
            $book->author()
        );
        $this->assertEquals(
            new Title(
                'Fruit Infused Water: 50 Quick & Easy Recipes for Delicious & ' .
                'Healthy Hydration'
            ),
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
        $book = $this->bookByIsbn('9781493634149');

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
        $book = $this->bookByIsbn('9781493634149');

        $book->textInChapter(2);

        $randomContent = 'Random content: ' . microtime() .' / '. rand();

        $this->insertTextIntoTheDatabase($randomContent);

        $this->assertEquals(
            "Purchase book for actual content of chapter 2...",
            $book->textInChapter(2)
        );
    }

    /** @scenario */
    function deriving_property_values_from_the_available_data()
    {
        $book = $this->bookByIsbn('9781493634149');

        $this->assertTrue($book->hasIsbnVersion13());
    }


    private function bookByIsbn($code) : Book
    {
        return $this->books->fromArray($this->selectBookDataByIsbn($code));
    }

    private function selectBookDataByIsbn(string $isbn) : array
    {
        $query = $this->database->prepare(
            "SELECT * FROM `book` WHERE `id` = :isbn"
        );
        $query->bindValue('isbn', $isbn);
        return $query->execute()->fetchArray(SQLITE3_ASSOC);
    }

    private function insertTextIntoTheDatabase(string $text) : void
    {
        $query = $this->database->prepare(
            "INSERT INTO `text` VALUES (
              1, 1, :content
            );"
        );
        $query->bindValue('content', $text);
        $query->execute();
    }

    private function setUpDatabase() : SQLite3
    {
        $database = new SQLite3(__DIR__.'/books.sqlite');
        foreach (require('Infrastructure/Database.php') as $statement) {
            $database->exec($statement);
        }
        return $database;
    }

    private function buildBookMapping(SQLite3 $database)
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
                ->containedInA(Chapters::class)
                ->loadedBy(ChapterLoaderFactory::withAccessTo($database,
                    Mapper::forThe(Chapter::class)
                        ->property('title')
                        ->property('elements', Has::many(Text::class)
                            ->with('text')
                            ->nested()
                            ->containedInA(Elements::class)
                        )
                        ->hydrator()
                ))
            )
            ->property('format')
            ->map();
    }

    protected function setUp() : void
    {
        $this->database = $this->setUpDatabase();
        $this->books = MappedHydrator::fromThis(
            $this->buildBookMapping($this->database)
        );
    }
}
