<?php

namespace Stratadox\Hydrate\Test\Model;

class Title
{
    private $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function title() : string
    {
        return $this->title;
    }

    public function __toString() : string
    {
        return $this->title();
    }
}