<?php

namespace Cora\Handlers\Session;

use Cora\Domain\Petrinet\Marking\MarkingNotFoundException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cora\Domain\Petrinet\PetrinetNotFoundException;
use Cora\Domain\Petrinet\PetrinetRepository as PetriRepo;
use Cora\Domain\Session\SessionRepository as SessionRepo;
use Cora\Domain\Session\View\SessionCreatedViewFactory;
use Cora\Domain\User\UserRepository as UserRepo;
use Cora\Domain\User\UserNotFoundException;
use Cora\Handlers\AbstractRequestHandler;
use Cora\Handlers\BadRequestException;
use Cora\Services\StartSessionService;

use Slim\HttpBadRequestException;

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
