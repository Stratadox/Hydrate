<?php

declare(strict_types=1);

namespace Stratadox\Hydrate\Test\Infrastructure\Hydrators;

use Stratadox\Hydrate\Test\Model\Title;
use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Hydrator\MappedHydrator;
use Stratadox\Hydration\Mapping\Mapping;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;

class TitleHydrator extends Decorator
{
    public static function create() : Hydrates
    {
        return new static(
            MappedHydrator::fromThis(Mapping::ofThe(Title::class,
                StringValue::inProperty('title')
            ))
        );
    }
}
