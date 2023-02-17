<?php

namespace Cora\Handlers\Feedback;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

use Cora\Handlers\AbstractRequestHandler;
use Cora\Services\GetFeedbackService;

class CoverabilityFeedback extends AbstractRequestHandler {
    public function handleRequest(Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
        $userId = $parsedBody["user_id"] ?? NULL;
        if (is_null($userId))
            throw new HttpBadRequestException("No user id provided");
        $sessionId = $parsedBody["session_id"] ?? NULL;
        if (is_null($sessionId))
            throw new HttpBadRequestException("No session id provided");
        $graph = $parsedBody["graph"] ?? NULL;
        if (is_null($graph))
            throw new HttpBadRequestException("No graph provided");

        $service = $this->container->get(GetFeedbackService::class);
        $result = $service->get($graph, $userId, $sessionId);

        $response->getBody()->write(json_encode($result));
        return $response->withHeader("Content-Type", "application/json");
    }
}
