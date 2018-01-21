<?php

declare(strict_types = 1);

namespace Stratadox\Hydrate\Test\Model;

use function sprintf;
use function strpos;

class Book
{
    private $title;
    private $author;
    private $isbn;
    private $contents;
    private $format;

    public function __construct(
        Title $ofTheBook,
        Author $whoWroteIt,
        Isbn $code,
        Chapters $inTheBook,
        string $format = null
    ) {
        $this->title = $ofTheBook;
        $this->author = $whoWroteIt;
        $this->isbn = $code;
        $this->contents = $inTheBook;
        $this->format = $format ?: "%s (by %s, ISBN %s)\n\n%s";
    }

    public function wasWrittenByThe(Author $inQuestion)
    {
        return $this->author == $inQuestion;
    }

    public function hasInItsTitle(string $searchText)
    {
        return strpos($this->title, $searchText) !== false;
    }

    public function hasIsbnVersion10()
    {
        return $this->isbn->isVersion10();
    }

    public function hasIsbnVersion13()
    {
        return $this->isbn->isVersion13();
    }

    public function title() : Title
    {
        return $this->title;
    }

    public function author() : Author
    {
        return $this->author;
    }

    public function isbn() : Isbn
    {
        return $this->isbn;
    }

    public function chapters() : Chapters
    {
        return $this->contents;
    }

    public function format() : string
    {
        return $this->format;
    }

    public function textInChapter(int $number) : Text
    {
        return $this->contents->textInChapter($number - 1);
    }

    public function __toString() : string
    {
        return sprintf($this->format,
            $this->title(),
            $this->author(),
            $this->isbn(),
            $this->chapters()
        );
    }
}
