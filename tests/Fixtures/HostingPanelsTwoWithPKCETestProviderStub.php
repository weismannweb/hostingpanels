<?php

namespace Laravel\HostingPanels\Tests\Fixtures;

class ServerTwoWithPKCETestProviderStub extends ServerTwoTestProviderStub
{
    protected $usesPKCE = true;
}
