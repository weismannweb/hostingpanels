<?php

namespace Laravel\HostingPanels\Contracts;

interface Factory
{
    /**
     * Get an Server provider implementation.
     *
     * @param  string  $driver
     * @return \Laravel\HostingPanels\Contracts\Provider
     */
    public function driver($driver = null);
}
