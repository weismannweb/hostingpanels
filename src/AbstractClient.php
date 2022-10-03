<?php

namespace WeismannWeb\HostingServices;

use ArrayAccess;
use Laravel\Services\Contracts\HostingService;

abstract class AbstractHostingServicesManager implements ArrayAccess, HostingService
{
    /**
     * The unique identifier for the user.
     *
     * @var mixed
     */
    public $ip;

    /**
     * The user's nickname / username.
     *
     * @var string
     */
    public $host;

    /**
     * The user's full name.
     *
     * @var string
     */
    public $secure;

    /**
     * The user's e-mail address.
     *
     * @var string
     */
    public $username;

    /**
     * The user's avatar image URL.
     *
     * @var string
     */
    public $password;

    /**
     * The user's raw attributes.
     *
     * @var array
     */
    public $accesshash;

    /**
     * The user's raw attributes.
     *
     * @var array
     */
    public $port;

    /**
     * Get the unique identifier for the user.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Get the nickname / username for the user.
     *
     * @return string
     */
    public function getSecure()
    {
        return $this->secure;
    }

    /**
     * Get the full name of the user.
     *
     * @return string
     */
    public function geUsername()
    {
        return $this->username;
    }

    /**
     * Get the e-mail address of the user.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the avatar / image URL for the user.
     *
     * @return string
     */
    public function getAccesshash()
    {
        return $this->accesshash;
    }

    /**
     * Get the raw user array.
     *
     * @return array
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set the raw user array from the provider.
     *
     * @param  array  $user
     * @return $this
     */
    public function setRaw(array $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Map the given array onto the servers's properties.
     *
     * @param  array  $attributes
     * @return $this
     */
    public function map(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    /**
     * Determine if the given raw user attribute exists.
     *
     * @param  string  $offset
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->user);
    }

    /**
     * Get the given key from the raw user.
     *
     * @param  string  $offset
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->user[$offset];
    }

    /**
     * Set the given attribute on the raw user array.
     *
     * @param  string  $offset
     * @param  mixed  $value
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $this->user[$offset] = $value;
    }

    /**
     * Unset the given value from the raw user array.
     *
     * @param  string  $offset
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->user[$offset]);
    }
}
