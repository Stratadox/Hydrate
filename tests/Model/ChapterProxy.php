<?php

namespace Stratadox\Hydrate\Test\Model;

use Stratadox\Hydration\Proxy;
use Stratadox\Hydration\Proxying\Proxying;

class ChapterProxy extends Chapter implements Proxy
{
    use Proxying;

    public function title() : Title
    {
        return $this->__load()->title();
    }

    public function text() : Text
    {
        return $this->__load()->text();
    }

    public function __toString() : string
    {
        return $this->__load()->__toString();
    }
}
