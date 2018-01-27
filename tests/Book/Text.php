<?php

declare(strict_types = 1);

namespace Stratadox\Hydrate\Test\Book;

use const PHP_EOL;

class Text implements Element
{
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public static function startEmpty() : Text
    {
        return new static('');
    }

    public function text() : string
    {
        return $this->text;
    }

    public function add(Text $toAdd, string $inBetween = PHP_EOL) : Text
    {
        return new Text($this->text() . $inBetween . $toAdd->text());
    }

    public function __toString() : string
    {
        return $this->text();
    }
}
