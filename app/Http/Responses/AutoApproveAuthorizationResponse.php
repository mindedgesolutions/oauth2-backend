<?php

namespace App\Http\Responses;

use Laravel\Passport\Contracts\AuthorizationViewResponse as AuthorizationViewResponseContract;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use League\OAuth2\Server\AuthorizationServer;
use Nyholm\Psr7\Response;

class AutoApproveAuthorizationResponse implements AuthorizationViewResponseContract, Responsable
{
    protected array $parameters = [];

    public function toResponse($request)
    {
        // Retrieve the stored AuthorizationRequest from session
        $authRequest = $request->session()->get('authRequest');

        if (! $authRequest) {
            abort(400, 'No authorization request found in session.');
        }

        // Mark as approved (skip consent screen)
        $authRequest->setAuthorizationApproved(true);

        // Run it through Passport's AuthorizationServer
        $server = app(AuthorizationServer::class);

        return $server->completeAuthorizationRequest(
            $authRequest,
            new Response()
        );
    }

    public function withParameters(array $parameters = []): static
    {
        $new = clone $this;
        $new->parameters = $parameters;
        return $new;
    }
}
