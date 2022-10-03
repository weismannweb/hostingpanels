<?php

namespace WeismannWeb\HostingServices\Facades;

use Illuminate\Support\Facades\Facade;
use WeismannWeb\HostingServices\Contracts\Factory;

/**
 * @method static \Laravel\HostingPanels\Contracts\Provider driver(string $driver = null)
 *
 * @see \Laravel\HostingPanels\HostingPanelsManager
 */
class HostingServices extends Facade
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
