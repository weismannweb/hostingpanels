<?php

namespace Laravel\HostingPanels\Two;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;

class TwitterProvider extends AbstractProvider
{
    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = ['users.read', 'tweet.read'];

    /**
     * Indicates if PKCE should be used.
     *
     * @var bool
     */
    protected $usesPKCE = true;

    /**
     * The separating character for the requested scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ' ';

    /**
     * The query encoding format.
     *
     * @var int
     */
    protected $encodingType = PHP_QUERY_RFC3986;

    /**
     * {@inheritdoc}
     */
    public function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://twitter.com/i/server2/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://api.twitter.com/2/server2/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://api.twitter.com/2/users/me', [
            RequestOptions::HEADERS => ['Authorization' => 'Bearer '.$token],
            RequestOptions::QUERY => ['user.fields' => 'profile_image_url'],
        ]);

        return Arr::get(json_decode($response->getBody(), true), 'data');
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['id'],
            'nickname' => $user['username'],
            'name' => $user['name'],
            'avatar' => $user['profile_image_url'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            RequestOptions::HEADERS => ['Accept' => 'application/json'],
            RequestOptions::AUTH => [$this->clientId, $this->clientSecret],
            RequestOptions::FORM_PARAMS => $this->getTokenFields($code),
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function getCodeFields($state = null)
    {
        $fields = parent::getCodeFields($state);

        if ($this->isStateless()) {
            $fields['state'] = 'state';
        }

        return $fields;
    }
}
