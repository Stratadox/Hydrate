<?php

declare(strict_types = 1);

namespace Stratadox\Hydrate\Test\Model;

class Chapter
{
    private $title;
    private $elements;

    public function __construct(Title $title, Elements $elements)
    {
        $this->title = $title;
        $this->elements = $elements;
    }

    public static function titled(string $title, Element ...$elements) : Chapter
    {
        return new static(new Title($title), new Elements(...$elements));
    }

    public function title() : Title
    {
        return $this->title;
    }

    public function elements() : Elements
    {
        return $this->elements;
    }

    public function text() : Text
    {
        $text = Text::startEmpty();
        $separator = '';
        foreach ($this->elements as $element) {
            if ($element instanceof Text) {
                $text = $text->add($element, $separator);
                $separator = PHP_EOL;
            }
        }
        return $text;
    }

    public function __toString() : string
    {
        return "{$this->title()}\n\n{$this->text()}";
    }
}
