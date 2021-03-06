<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2019 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace OAuth2Framework\Component\AuthorizationEndpoint\ResponseMode;

use Assert\Assertion;
use function Safe\sprintf;

class ResponseModeManager
{
    /**
     * @var ResponseMode[]
     */
    private array $responseModes = [];

    /**
     * @return string[]
     */
    public function list(): array
    {
        return array_keys($this->responseModes);
    }

    /**
     * @return ResponseMode[]
     */
    public function all(): array
    {
        return array_values($this->responseModes);
    }

    public function add(ResponseMode $responseMode): void
    {
        $this->responseModes[$responseMode->name()] = $responseMode;
    }

    public function has(string $name): bool
    {
        return \array_key_exists($name, $this->responseModes);
    }

    public function get(string $name): ResponseMode
    {
        Assertion::true($this->has($name), sprintf('The response mode with name "%s" is not supported.', $name));

        return $this->responseModes[$name];
    }
}
