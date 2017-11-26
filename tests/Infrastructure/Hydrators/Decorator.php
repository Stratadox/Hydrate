<?php

declare(strict_types=1);

namespace Stratadox\Hydrate\Test\Infrastructure\Hydrators;

use Stratadox\Hydration\Hydrates;

abstract class Decorator implements Hydrates
{
    private $hydrator;

    protected function __construct(Hydrates $wrappedHydrator)
    {
        $this->hydrator = $wrappedHydrator;
    }

    public function fromArray(array $input)
    {
        return $this->hydrator->fromArray($input);
    }
}
