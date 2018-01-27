<?php

declare(strict_types = 1);

namespace Stratadox\Hydration\Test\Authors;

class Profile
{
    private $for;
    private $description;

    public function __construct(Author $for, string $description)
    {
        $this->for = $for;
        $this->description = $description;
    }

    public function for () : Author
    {
        return $this->for;
    }

    public function description() : string
    {
        return $this->description;
    }
}
