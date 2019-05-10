<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2019 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace OAuth2Framework\Component\AuthorizationEndpoint\Tests\AuthorizationRequest;

use OAuth2Framework\Component\AuthorizationEndpoint\AuthorizationRequest\AuthorizationRequest;
use OAuth2Framework\Component\AuthorizationEndpoint\ResponseMode\ResponseMode;
use OAuth2Framework\Component\AuthorizationEndpoint\ResponseType\ResponseType;
use OAuth2Framework\Component\Core\Client\Client;
use OAuth2Framework\Component\Core\DataBag\DataBag;
use OAuth2Framework\Component\Core\ResourceServer\ResourceServer;
use OAuth2Framework\Component\Core\TokenType\TokenType;
use OAuth2Framework\Component\Core\UserAccount\UserAccount;
use PHPUnit\Framework\TestCase;

/**
 * @group AuthorizationEndpoint
 * @group AuthorizationRequest
 */
final class AuthorizationRequestTest extends TestCase
{
    /**
     * @test
     */
    public function basicCalls()
    {
        $client = $this->prophesize(Client::class);
        $tokenType = $this->prophesize(TokenType::class);
        $responseType = $this->prophesize(ResponseType::class);
        $responseMode = $this->prophesize(ResponseMode::class);
        $userAccount = $this->prophesize(UserAccount::class);
        $resourceServer = $this->prophesize(ResourceServer::class);
        $params = [
            'prompt' => 'consent login select_account',
            'ui_locales' => 'fr en',
            'scope' => 'scope1 scope2',
        ];
        $authorizationRequest = new AuthorizationRequest($client->reveal(), $params);

        $authorizationRequest->setTokenType($tokenType->reveal());
        $authorizationRequest->setResponseType($responseType->reveal());
        $authorizationRequest->setResponseMode($responseMode->reveal());
        $authorizationRequest->setRedirectUri('https://localhost');
        $authorizationRequest->setUserAccount($userAccount->reveal());
        $authorizationRequest->setResponseParameter('foo', 'bar');
        $authorizationRequest->setResponseHeader('X-FOO', 'bar');
        $authorizationRequest->setResourceServer($resourceServer->reveal());
        $authorizationRequest->setConsentScreenOption('foo', 'bar');

        static::assertEquals($params, $authorizationRequest->getQueryParams());
        static::assertFalse($authorizationRequest->hasQueryParam('client_id'));
        static::assertTrue($authorizationRequest->hasQueryParam('prompt'));
        static::assertEquals('consent login select_account', $authorizationRequest->getQueryParam('prompt'));
        static::assertInstanceOf(Client::class, $authorizationRequest->getClient());
        static::assertInstanceOf(TokenType::class, $authorizationRequest->getTokenType());
        static::assertInstanceOf(ResponseType::class, $authorizationRequest->getResponseType());
        static::assertInstanceOf(ResponseMode::class, $authorizationRequest->getResponseMode());
        static::assertEquals('https://localhost', $authorizationRequest->getRedirectUri());
        static::assertInstanceOf(UserAccount::class, $authorizationRequest->getUserAccount());
        static::assertEquals(['foo' => 'bar'], $authorizationRequest->getResponseParameters());
        static::assertFalse($authorizationRequest->hasResponseParameter('bar'));
        static::assertTrue($authorizationRequest->hasResponseParameter('foo'));
        static::assertEquals('bar', $authorizationRequest->getResponseParameter('foo'));
        static::assertEquals(['X-FOO' => 'bar'], $authorizationRequest->getResponseHeaders());
        static::assertFalse($authorizationRequest->hasPrompt('none'));
        static::assertTrue($authorizationRequest->hasPrompt('login'));
        static::assertEquals(['consent', 'login', 'select_account'], $authorizationRequest->getPrompt());
        static::assertTrue($authorizationRequest->hasUiLocales());
        static::assertEquals(['fr', 'en'], $authorizationRequest->getUiLocales());
        $authorizationRequest->allow();
        static::assertTrue($authorizationRequest->isAuthorized());
        $authorizationRequest->deny();
        static::assertFalse($authorizationRequest->isAuthorized());
        static::assertInstanceOf(ResourceServer::class, $authorizationRequest->getResourceServer());
        static::assertTrue($authorizationRequest->hasScope());
        static::assertEquals('scope1 scope2', $authorizationRequest->getScope());
        static::assertInstanceOf(DataBag::class, $authorizationRequest->getMetadata());
    }
}
