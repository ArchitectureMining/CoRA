<?php

namespace Cora\Handlers\Session;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

use Cora\Handlers\AbstractRequestHandler;
use Cora\Services\GetSessionService;

class GetCurrentSession extends AbstractRequestHandler {
    public function handleRequest(Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
        $userId = $parsedBody["user_id"] ?? NULL;
        if (is_null($userId))
            throw new HttpNotFoundException("No user id supplied");

        $service = $this->container->get(GetSessionService::class);
        $result = $service->get($userId);

        if (is_null($result))
            throw new HttpNotFoundException("No session found");

        $response->getBody()->write(json_encode($result));
        return $response->withHeader("Content-Type", "application/json");
    }
}
