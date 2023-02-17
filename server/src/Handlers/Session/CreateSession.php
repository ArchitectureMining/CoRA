<?php

namespace Cora\Handlers\Session;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

use Cora\Handlers\AbstractRequestHandler;
use Cora\Services\StartSessionService;

class CreateSession extends AbstractRequestHandler {
    public function handleRequest(Request $request, Response $response, $args) {
        $parsedBody= $request->getParsedBody();
        $userId = $parsedBody["user_id"] ?? NULL;
        if (is_null($userId))
            throw new HttpBadRequestException("No user id provided");
        $petrinetId = $parsedBody["petrinet_id"] ?? NULL;
        if (is_null($petrinetId))
            throw new HttpBadRequestException("No Petri net id provided");
        $markingId = $parsedBody["marking_id"] ??  NULL;
        if (is_null($markingId))
            throw new HttpBadRequestException("No marking id provided");

        $service = $this->container->get(StartSessionService::class);
        $sessionId = $service->start($userId, $petrinetId, $markingId);

        $response->getBody()->write(json_encode($sessionId));
        return $response->withHeader("Content-Type", "application/json")
                        ->withStatus(201);
    }
}
