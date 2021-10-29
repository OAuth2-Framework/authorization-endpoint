<?php

declare(strict_types=1);

namespace OAuth2Framework\Component\AuthorizationEndpoint\Extension;

use OAuth2Framework\Component\AuthorizationEndpoint\AuthorizationRequest\AuthorizationRequest;
use Psr\Http\Message\ServerRequestInterface;

class ExtensionManager
{
    /**
     * @var Extension[]
     */
    private array $extensions = [];

    public function add(Extension $extension): void
    {
        $this->extensions[] = $extension;
    }

    public function process(ServerRequestInterface $request, AuthorizationRequest $authorization): void
    {
        foreach ($this->extensions as $extension) {
            $extension->process($request, $authorization);
        }
    }
}
