<?php

declare(strict_types = 1);

namespace Stratadox\Hydrate\Test\Model;

class Author
{
    private $firstName;
    private $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public static function named(string $firstName, string $lastName)
    {
        return new static($firstName, $lastName);
    }

    public function firstName() : string
    {
        return $this->firstName;
    }

    public function lastName() : string
    {
        return $this->lastName;
    }

    public function __toString() : string
    {
        return "{$this->firstName()} {$this->lastName()}";
    }
}
