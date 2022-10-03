<?php

namespace Laravel\HostingPanels\Tests;

use Laravel\HostingPanels\Contracts\Factory;
use Laravel\HostingPanels\HostingPanelsServiceProvider;
use Laravel\HostingPanels\Two\GithubProvider;
use Orchestra\Testbench\TestCase;

class HostingPanelsManagerTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('services.github', [
            'client_id' => 'github-client-id',
            'client_secret' => 'github-client-secret',
            'redirect' => 'http://your-callback-url',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [HostingPanelsServiceProvider::class];
    }

    public function test_it_can_instantiate_the_github_driver()
    {
        $factory = $this->app->make(Factory::class);

        $provider = $factory->driver('github');

        $this->assertInstanceOf(GithubProvider::class, $provider);
    }
}
