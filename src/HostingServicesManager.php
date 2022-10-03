<?php

namespace Laravel\HostingServices;

use Illuminate\Support\Arr;
use Illuminate\Support\Manager;
use Illuminate\Support\Str;
use InvalidArgumentException;



class HostingServicesManager extends HostingService implements Contracts\Factory
{
    
    /**
     * Get a driver instance.
     *
     * @param  string  $driver
     * @return mixed
     */
    public function with($driver)
    {
        return $this->driver($driver);
    }


}
