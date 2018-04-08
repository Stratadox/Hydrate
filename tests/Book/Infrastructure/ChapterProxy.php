<?php

declare(strict_types=1);

namespace Stratadox\Hydrate\Test\Book\Infrastructure;

use Stratadox\Hydrate\Test\Book\Chapter;
use Stratadox\Hydrate\Test\Book\Elements;
use Stratadox\Hydrate\Test\Book\Text;
use Stratadox\Hydrate\Test\Book\Title;
use Stratadox\Proxy\Proxy;
use Stratadox\Proxy\Proxying;

class ChapterProxy extends Chapter implements Proxy
{
    use Proxying;

    public function title(): Title
    {
        return $this->__load()->title();
    }

    public function elements(): Elements
    {
        return $this->__load()->elements();
    }

    public function text(): Text
    {
        return $this->__load()->text();
    }

    public function __toString(): string
    {
        return $this->__load()->__toString();
    }
}
