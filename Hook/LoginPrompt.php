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

namespace OAuth2Framework\Component\AuthorizationEndpoint\Hook;

use OAuth2Framework\Component\AuthorizationEndpoint\AuthorizationRequest\AuthorizationRequest;
use OAuth2Framework\Component\AuthorizationEndpoint\User\UserAuthenticationCheckerManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class LoginPrompt implements AuthorizationEndpointHook
{
    /**
     * @var UserAuthenticationCheckerManager
     */
    private $userAuthenticationCheckerManager;

    /**
     * LoginPrompt constructor.
     */
    public function __construct(UserAuthenticationCheckerManager $userAuthenticationCheckerManager)
    {
        $this->userAuthenticationCheckerManager = $userAuthenticationCheckerManager;
    }

    public function handle(ServerRequestInterface $request, AuthorizationRequest $authorizationRequest, string $authorizationRequestId): ?ResponseInterface
    {
        $isAuthenticationNeeded = $this->userAuthenticationCheckerManager->isAuthenticationNeeded($authorizationRequest);
        if (!$isAuthenticationNeeded || !$authorizationRequest->hasPrompt('login')) {
            return null;
        }

        if ($authorizationRequest->hasAttribute('user_has_been_authenticated') && true === $authorizationRequest->getAttribute('user_has_been_authenticated')) {
            return null;
        }

        return $this->processWithLoginResponse($request, $authorizationRequestId, $authorizationRequest);
    }

    abstract protected function processWithLoginResponse(ServerRequestInterface $request, string $authorizationRequestId, AuthorizationRequest $authorizationRequest): ?ResponseInterface;
}