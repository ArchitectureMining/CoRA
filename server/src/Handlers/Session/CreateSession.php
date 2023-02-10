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

class CreateSession extends AbstractRequestHandler {
    public function handle(Request $request, Response $response, $args) {
        try {
            $parsedBody= $request->getParsedBody();
            $userId = $parsedBody["user_id"] ?? NULL;
            if (is_null($userId))
                throw new BadRequestException("No user id provided");
            $netId = $parsedBody["petrinet_id"] ?? NULL;
            if (is_null($netId))
                throw new BadRequestException("No Petri net id provided");
            $markingId = $parsedBody["marking_id"] ??  NULL;
            if (is_null($markingId))
                throw new BadRequestException("No marking id provided");
            $service     = $this->container->get(StartSessionService::class);
            $userRepo    = $this->container->get(UserRepo::class);
            $petriRepo   = $this->container->get(PetriRepo::class);
            $sessionRepo = $this->container->get(SessionRepo::class);
            $mediaType   = $this->getMediaType($request);
            $view        = $this->getView($mediaType);
            $service->start(
                $view,
                $userId,
                $netId,
                $markingId,
                $sessionRepo,
                $userRepo,
                $petriRepo
            );
            $response->getBody()->write($view->render());
            return $response->withHeader("Content-type", $mediaType)
                            ->withStatus(201);
        } catch (UserNotFoundException |
                 PetrinetNotFoundException |
                 MarkingNotFoundException $e) {
            return $this->fail($request, $response, $e, 404);
        }
    }

    protected function getViewFactory(): \Cora\Views\AbstractViewFactory {
        return new SessionCreatedViewFactory();
    }
}
