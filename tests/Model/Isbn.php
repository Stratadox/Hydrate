<?php

declare(strict_types = 1);

namespace Stratadox\Hydrate\Test\Model;

class Isbn
{
    private const VERSION_10 = 10;
    private const VERSION_13 = 13;

    private $code;
    private $version;

    public function __construct(string $code)
    {
        $this->code = $this->removeThePrettyFormattingOfThe($code);
        $this->version = $this->detectTheVersionOf($this->code);
    }

    public function code() : string
    {
        return $this->code;
    }

    public function isVersion10() : bool
    {
        return $this->version === static::VERSION_10;
    }

    public function isVersion13() : bool
    {
        return $this->version === static::VERSION_13;
    }

    public function __toString() : string
    {
        return $this->code();
    }

    private function removeThePrettyFormattingOfThe(string $code) : string
    {
        return str_replace(' ', '', str_replace('_', '', $code));
    }

    private function detectTheVersionOf(string $theCode) : int
    {
        switch (strlen($theCode)) {
            case 10: return static::VERSION_10;
            case 13: return static::VERSION_13;
        }
        throw new IsbnMustBeValid('Unrecognised isbn version.');
    }
}
