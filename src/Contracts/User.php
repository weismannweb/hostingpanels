<?php

namespace Laravel\HostingPanels\Contracts;

interface User
{
     /**
     * @return string
     */
    public function getLoginUrl();

    /**
     * @return string
     */
    public function getResellerLoginUrl();
}
