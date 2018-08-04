<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2018 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace OAuth2Framework\Component\AuthorizationEndpoint;

class ResponseTypeManager
{
    /**
     * @var ResponseType[]
     */
    private $responseTypes = [];

    /**
     * @return ResponseTypeManager
     */
    public function add(ResponseType $responseType): self
    {
        $this->responseTypes[$responseType->name()] = $responseType;

        return $this;
    }

    public function has(string $responseType): bool
    {
        return \array_key_exists($responseType, $this->responseTypes);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function get(string $responseType): ResponseType
    {
        if (!$this->has($responseType)) {
            throw new \InvalidArgumentException(\sprintf('The response type "%s" is not supported.', $responseType));
        }

        return $this->responseTypes[$responseType];
    }

    /**
     * @return string[]
     */
    public function list(): array
    {
        return \array_keys($this->responseTypes);
    }

    /**
     * @return string[]
     */
    public function all(): array
    {
        return \array_values($this->responseTypes);
    }
}
