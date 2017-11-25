<?php

declare(strict_types=1);

namespace Stratadox\Hydrate\Test\Infrastructure;

use Closure;
use Stratadox\Collection\Alterable;
use Stratadox\Hydration\UpdatesTheProxyOwner;

/**
 * Updates a property with the newly loaded value.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 * @todo Incorporate in hydration proxies as eg. AlterableCollectionUpdater
 */
class ContentsUpdater implements UpdatesTheProxyOwner
{
    private $owner;
    private $propertyShouldReference;
    private $atPosition;
    private $setter;

    public function __construct(
        $theOwner,
        string $theProperty,
        $atPosition,
        Closure $setter = null
    ) {
        $this->owner = $theOwner;
        $this->propertyShouldReference = $theProperty;
        $this->atPosition = $atPosition;
        $this->setter = $setter ?: function (string $property, $value, $position) : void
        {
            $this->$property = $this->$property->change($position, $value);
        };
    }

    public static function for(
        $theOwner,
        string $ofTheProperty,
        $atPosition,
        Closure $setter = null
    ) : UpdatesTheProxyOwner
    {
        return new static($theOwner, $ofTheProperty, $atPosition, $setter);
    }

    public function updateWith($theLoadedInstance) : void
    {
        $this->setter->call($this->owner,
            $this->propertyShouldReference,
            $theLoadedInstance,
            $this->atPosition
        );
    }
}