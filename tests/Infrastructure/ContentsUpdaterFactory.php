<?php

declare(strict_types=1);

namespace Stratadox\Hydrate\Test\Infrastructure;

use Stratadox\Hydration\ProducesOwnerUpdaters;
use Stratadox\Hydration\UpdatesTheProxyOwner;

/**
 * Produces @see PropertyUpdater instances.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
class ContentsUpdaterFactory implements ProducesOwnerUpdaters
{
    public function makeUpdaterFor(
        $theOwner,
        string $ofTheProperty,
        $atPosition = null
    ) : UpdatesTheProxyOwner
    {
        return ContentsUpdater::for($theOwner, $ofTheProperty, $atPosition);
    }
}
