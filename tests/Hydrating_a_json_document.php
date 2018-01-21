<?php

declare(strict_types=1);

namespace Stratadox\Hydrate\Test;

use function file_get_contents;
use function json_decode;
use PHPUnit\Framework\TestCase;
use Stratadox\Hydrate\Test\Model\Author;
use Stratadox\Hydrate\Test\Model\Book;
use Stratadox\Hydrate\Test\Model\Chapter;
use Stratadox\Hydrate\Test\Model\Chapters;
use Stratadox\Hydrate\Test\Model\Element;
use Stratadox\Hydrate\Test\Model\Elements;
use Stratadox\Hydrate\Test\Model\Image;
use Stratadox\Hydrate\Test\Model\Isbn;
use Stratadox\Hydrate\Test\Model\Text;
use Stratadox\Hydrate\Test\Model\Title;
use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Mapper\Instruction\Call;
use Stratadox\Hydration\Mapper\Instruction\Has;
use Stratadox\Hydration\Mapper\Instruction\In;
use Stratadox\Hydration\Mapper\Instruction\Relation\Choose;
use Stratadox\Hydration\Mapper\Mapper;

class Hydrating_a_json_document extends TestCase
{
    /** @var Hydrates */
    private $books;

    /** @scenario */
    function hydrating_a_json_string_into_an_object_structure()
    {
        $result = json_decode(file_get_contents(__DIR__.'/books.json'), true);

        /** @var Book[] $books */
        $books = [];
        foreach ($result['books'] as $data) {
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
        $this->assertSame(
            'Purchase book for actual content of chapter 2...',
            (string) $books[0]->chapters()[1]->text()
        );
        $this->assertSame(
            'Graphic #1',
            (string) $books[0]->chapters()[1]->elements()[1]
        );
    }

    protected function setUp()
    {
        $this->books = Mapper::forThe(Book::class)
            ->property('title', Has::one(Title::class)->with('title'))
            ->property('isbn', Has::one(Isbn::class)
                ->with('code', In::key('id'))
                ->with('version', Call::the(function ($data) {
                    return strlen($data['id']);
                }))
            )
            ->property('author', Has::one(Author::class)
                ->nested()
                ->with('firstName')
                ->with('lastName')
            )
            ->property('contents',
                Has::many(Chapter::class, In::key('chapters'))
                    ->containedInA(Chapters::class)
                    ->nested()
                    ->with('elements', Has::many(Element::class)
                        ->selectBy('type',
                            [
                                'text' => Choose::the(Text::class)
                                    ->with('text'),
                                'image' => Choose::the(Image::class)
                                    ->with('src')
                                    ->with('alt'),
                            ]
                        )
                        ->nested()
                        ->containedInA(Elements::class)
                    )
            )
            ->property('format')
            ->hydrator();
    }
}
