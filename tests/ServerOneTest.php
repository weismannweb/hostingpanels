<?php

namespace Laravel\HostingPanels\Tests;

use Illuminate\Contracts\Session\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\HostingPanels\One\MissingTemporaryCredentialsException;
use Laravel\HostingPanels\One\MissingVerifierException;
use Laravel\HostingPanels\One\User as HostingPanelsUser;
use Laravel\HostingPanels\Tests\Fixtures\ServerOneTestProviderStub;
use League\Server1\Client\Credentials\TemporaryCredentials;
use League\Server1\Client\Credentials\TokenCredentials;
use League\Server1\Client\Server\Twitter;
use League\Server1\Client\Server\User;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class ServerOneTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        m::close();
    }

    public function testRedirectGeneratesTheProperIlluminateRedirectResponse()
    {
        $server = m::mock(Twitter::class);
        $temp = m::mock(TemporaryCredentials::class);
        $server->expects('getTemporaryCredentials')->andReturns($temp);
        $server->expects('getAuthorizationUrl')->with($temp)->andReturns('http://auth.url');
        $request = Request::create('foo');
        $request->setLaravelSession($session = m::mock(Session::class));
        $session->expects('put')->with('server.temp', $temp);

        $provider = new ServerOneTestProviderStub($request, $server);
        $response = $provider->redirect();

        $this->assertInstanceOf(SymfonyRedirectResponse::class, $response);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testUserReturnsAUserInstanceForTheAuthenticatedRequest()
    {
        $server = m::mock(Twitter::class);
        $temp = m::mock(TemporaryCredentials::class);
        $server->expects('getTokenCredentials')->with($temp, 'server_token', 'server_verifier')->andReturns(
            $token = m::mock(TokenCredentials::class)
        );
        $server->expects('getUserDetails')->with($token, false)->andReturns($user = m::mock(User::class));
        $token->expects('getIdentifier')->twice()->andReturns('identifier');
        $token->expects('getSecret')->twice()->andReturns('secret');
        $user->uid = 'uid';
        $user->email = 'foo@bar.com';
        $user->extra = ['extra' => 'extra'];
        $request = Request::create('foo', 'GET', ['server_token' => 'server_token', 'server_verifier' => 'server_verifier']);
        $request->setLaravelSession($session = m::mock(Session::class));
        $session->expects('get')->with('server.temp')->andReturns($temp);

        $provider = new ServerOneTestProviderStub($request, $server);
        $user = $provider->user();

        $this->assertInstanceOf(HostingPanelsUser::class, $user);
        $this->assertSame('uid', $user->id);
        $this->assertSame('foo@bar.com', $user->email);
        $this->assertSame(['extra' => 'extra'], $user->user);
    }

    public function testExceptionIsThrownWhenVerifierIsMissing()
    {
        $this->expectException(MissingVerifierException::class);

        $server = m::mock(Twitter::class);
        $request = Request::create('foo');
        $request->setLaravelSession(m::mock(Session::class));

        $provider = new ServerOneTestProviderStub($request, $server);
        $provider->user();
    }

    public function testExceptionIsThrownWhenTemporaryCredentialsAreMissing()
    {
        $this->expectException(MissingTemporaryCredentialsException::class);

        $server = m::mock(Twitter::class);
        $request = Request::create('foo', 'GET', ['server_token' => 'server_token', 'server_verifier' => 'server_verifier']);
        $request->setLaravelSession($session = m::mock(Session::class));
        $session->expects('get')->with('server.temp')->andReturns(null);

        $provider = new ServerOneTestProviderStub($request, $server);
        $provider->user();
    }
}
