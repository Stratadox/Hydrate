<?php

declare(strict_types=1);

namespace Stratadox\Hydrate\Test\Book;

use Stratadox\Collection\Alterable;
use Stratadox\ImmutableCollection\Altering;
use Stratadox\ImmutableCollection\ImmutableCollection;

class Elements extends ImmutableCollection implements Alterable
{
    use Altering;

    public function __construct(Element ...$elements)
    {
        parent::__construct(...$elements);
    }

    public function current(): Element
    {
        return parent::current();
    }
}
