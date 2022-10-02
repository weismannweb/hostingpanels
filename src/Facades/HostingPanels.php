<?php

namespace Laravel\HostingPanels\Facades;

use Illuminate\Support\Facades\Facade;
use Laravel\HostingPanels\Contracts\Factory;

/**
 * @method static \Laravel\HostingPanels\Contracts\Provider driver(string $driver = null)
 *
 * @see \Laravel\HostingPanels\HostingPanelsManager
 */
class HostingPanels extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
