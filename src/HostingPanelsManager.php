<?php

namespace Laravel\HostingPanels;

use Illuminate\Support\Arr;
use Illuminate\Support\Manager;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Laravel\HostingPanels\One\TwitterProvider;
use Laravel\HostingPanels\Two\BitbucketProvider;
use Laravel\HostingPanels\Two\FacebookProvider;
use Laravel\HostingPanels\Two\GithubProvider;
use Laravel\HostingPanels\Two\GitlabProvider;
use Laravel\HostingPanels\Two\GoogleProvider;
use Laravel\HostingPanels\Two\LinkedInProvider;
use Laravel\HostingPanels\Two\TwitterProvider as TwitterServer2Provider;
use League\Server1\Client\Server\Twitter as TwitterServer;

class HostingPanelsManager extends Manager implements Contracts\Factory
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

    /**
     * Create an instance of the specified driver.
     *
     * @return \Laravel\HostingPanels\Two\AbstractProvider
     */
    protected function createGithubDriver()
    {
        $config = $this->config->get('services.github');

        return $this->buildProvider(
            GithubProvider::class, $config
        );
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Laravel\HostingPanels\Two\AbstractProvider
     */
    protected function createFacebookDriver()
    {
        $config = $this->config->get('services.facebook');

        return $this->buildProvider(
            FacebookProvider::class, $config
        );
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Laravel\HostingPanels\Two\AbstractProvider
     */
    protected function createGoogleDriver()
    {
        $config = $this->config->get('services.google');

        return $this->buildProvider(
            GoogleProvider::class, $config
        );
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Laravel\HostingPanels\Two\AbstractProvider
     */
    protected function createLinkedinDriver()
    {
        $config = $this->config->get('services.linkedin');

        return $this->buildProvider(
          LinkedInProvider::class, $config
        );
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Laravel\HostingPanels\Two\AbstractProvider
     */
    protected function createBitbucketDriver()
    {
        $config = $this->config->get('services.bitbucket');

        return $this->buildProvider(
          BitbucketProvider::class, $config
        );
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Laravel\HostingPanels\Two\AbstractProvider
     */
    protected function createGitlabDriver()
    {
        $config = $this->config->get('services.gitlab');

        return $this->buildProvider(
            GitlabProvider::class, $config
        )->setHost($config['host'] ?? null);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Laravel\HostingPanels\One\AbstractProvider|\Laravel\HostingPanels\Two\AbstractProvider
     */
    protected function createTwitterDriver()
    {
        $config = $this->config->get('services.twitter');

        if (($config['server'] ?? null) === 2) {
            return $this->createTwitterServer2Driver();
        }

        return new TwitterProvider(
            $this->container->make('request'), new TwitterServer($this->formatConfig($config))
        );
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Laravel\HostingPanels\Two\AbstractProvider
     */
    protected function createTwitterServer2Driver()
    {
        $config = $this->config->get('services.twitter') ?? $this->config->get('services.twitter-server-2');

        return $this->buildProvider(
            TwitterServer2Provider::class, $config
        );
    }

    /**
     * Build an Server 2 provider instance.
     *
     * @param  string  $provider
     * @param  array  $config
     * @return \Laravel\HostingPanels\Two\AbstractProvider
     */
    public function buildProvider($provider, $config)
    {
        return new $provider(
            $this->container->make('request'), $config['client_id'],
            $config['client_secret'], $this->formatRedirectUrl($config),
            Arr::get($config, 'guzzle', [])
        );
    }

    /**
     * Format the server configuration.
     *
     * @param  array  $config
     * @return array
     */
    public function formatConfig(array $config)
    {
        return array_merge([
            'identifier' => $config['client_id'],
            'secret' => $config['client_secret'],
            'callback_uri' => $this->formatRedirectUrl($config),
        ], $config);
    }

    /**
     * Format the callback URL, resolving a relative URI if needed.
     *
     * @param  array  $config
     * @return string
     */
    protected function formatRedirectUrl(array $config)
    {
        $redirect = value($config['redirect']);

        return Str::startsWith($redirect, '/')
                    ? $this->container->make('url')->to($redirect)
                    : $redirect;
    }

    /**
     * Forget all of the resolved driver instances.
     *
     * @return $this
     */
    public function forgetDrivers()
    {
        $this->drivers = [];

        return $this;
    }

    /**
     * Set the container instance used by the manager.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return $this
     */
    public function setContainer($container)
    {
        $this->app = $container;
        $this->container = $container;

        return $this;
    }

    /**
     * Get the default driver name.
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getDefaultDriver()
    {
        throw new InvalidArgumentException('No HostingPanels driver was specified.');
    }
}
