<?php

declare(strict_types=1);

namespace Stratadox\Hydrate\Test\Infrastructure\Hydrators;

use Stratadox\Hydrate\Test\Model\Isbn;
use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Hydrator\MappedHydrator;
use Stratadox\Hydration\Mapping\Mapping;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;

class IsbnHydrator extends Decorator
{
    public static function create() : Hydrates
    {
        return new static(
            MappedHydrator::fromThis(Mapping::ofThe(Isbn::class,
                StringValue::inPropertyWithDifferentKey('code', 'id')
            ))
        );
    }
}
