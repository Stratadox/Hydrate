<?php

declare(strict_types = 1);

namespace Stratadox\Hydrate\Test\Book;

use function implode;
use Stratadox\Collection\Alterable;
use Stratadox\ImmutableCollection\Altering;
use Stratadox\ImmutableCollection\ImmutableCollection;

class Chapters extends ImmutableCollection implements Alterable
{
    use Altering;

    public function __construct(Chapter ...$chapters)
    {
        parent::__construct(...$chapters);
    }

    public static function are(Chapter ...$chapters)
    {
        return new static(...$chapters);
    }

    public function textInChapter(int $index) : Text
    {
        return $this->textFromChapterAt($this[$index]);
    }

    public function textFromChapterAt(Chapter $selected) : Text
    {
        return $selected->text();
    }

    public function __toString() : string
    {
        return implode("\n\n", $this->toArray());
    }

    public function current() : Chapter
    {
        return parent::current();
    }
}
