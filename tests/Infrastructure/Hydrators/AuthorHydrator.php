<?php

declare(strict_types=1);

namespace Stratadox\Hydrate\Test\Infrastructure\Hydrators;

use Stratadox\Hydrate\Test\Model\Author;
use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Hydrator\MappedHydrator;
use Stratadox\Hydration\Mapping\Mapping;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;

class AuthorHydrator extends Decorator
{
    public static function create() : Hydrates
    {
        return new static(
            MappedHydrator::fromThis(Mapping::ofThe(Author::class,
                StringValue::inPropertyWithDifferentKey('firstName', 'author_first_name'),
                StringValue::inPropertyWithDifferentKey('lastName', 'author_last_name')
            ))
        );
    }
}
