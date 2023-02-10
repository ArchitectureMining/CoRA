<?php

namespace Cora\Handlers\Feedback;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cora\Handlers\AbstractRequestHandler;
use Cora\Handlers\BadRequestException;
use Cora\Domain\Feedback\View\FeedbackViewFactory;
use Cora\Domain\Petrinet\PetrinetRepository as PetrinetRepo;
use Cora\Domain\Session\InvalidSessionException;
use Cora\Domain\Session\NoSessionLogException;
use Cora\Domain\Session\SessionRepository as SessionRepo;
use Cora\Services\GetFeedbackService;

class CoverabilityFeedback extends AbstractRequestHandler {
    public function handle(Request $request, Response $response, $args) {
        try {
            $parsedBody = $request->getParsedBody();
            $userId = $parsedBody["user_id"] ?? NULL;
            if (is_null($userId))
                throw new BadRequestException("No user id provided");
            $sessionId = $parsedBody["session_id"] ?? NULL;
            if (is_null($sessionId))
                throw new BadRequestException("No session id provided");
            $graph = $parsedBody["graph"] ?? NULL;
            if (is_null($graph))
                throw new BadRequestException("No graph provided");
            $petriRepo   = $this->container->get(PetrinetRepo::class);
            $sessionRepo = $this->container->get(SessionRepo::class);
            $service     = $this->container->get(GetFeedbackService::class);
            $mediaType   = $this->getMediaType($request);
            $view        = $this->getView($mediaType);
            $service->get(
                $view,
                $graph,
                $userId,
                $sessionId,
                $petriRepo,
                $sessionRepo
            );

            $response->getBody()->write($view->render());
            return $response->withHeader("Content-type", $mediaType)
                            ->withStatus(200);
        } catch(InvalidSessionException |
                NoSessionLogException $e) {
            return $this->fail($request, $response, $e, 404);
        }
    }

    protected function getViewFactory(): \Cora\Views\AbstractViewFactory {
        return new FeedbackViewFactory();
    }
}
