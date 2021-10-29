<?php

declare(strict_types=1);

namespace OAuth2Framework\Component\AuthorizationEndpoint\ResponseMode;

interface FormPostResponseRenderer
{
    public function render(string $redirectUri, array $data): string;
}
