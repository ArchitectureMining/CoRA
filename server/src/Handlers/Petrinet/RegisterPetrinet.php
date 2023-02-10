<?php

namespace Cora\Handlers\Petrinet;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cora\Domain\User\UserRepository as UserRepo;
use Cora\Domain\User\Exception\UserNotFoundException;
use Cora\Domain\Petrinet\PetrinetRepository as PetrinetRepo;
use Cora\Domain\Petrinet\View\PetrinetCreatedViewFactory;
use Cora\Handlers\AbstractRequestHandler;
use Cora\Handlers\BadRequestException;
use Cora\Services\RegisterPetrinetService;

class RegisterPetrinet extends AbstractRequestHandler {
    public function handle(Request $request, Response $response, $args) {
        try {
            $parsedBody = $request->getParsedBody();
            if (!isset($parsedBody["user_id"]))
                throw new BadRequestException("No user id");
            $userId = $parsedBody["user_id"];
            $files = $request->getUploadedFiles();
            if (!isset($files["petrinet"]))
                throw new BadRequestException("No Petri net uploaded");
            $file         = $files["petrinet"];
            $petrinetRepo = $this->container->get(PetrinetRepo::class);
            $userRepo     = $this->container->get(UserRepo::class);
            $mediaType    = $this->getMediaType($request);
            $view         = $this->getView($mediaType);
            $service      = $this->container->get(RegisterPetrinetService::class);
            $service->register(
                $view,
                $userId,
                $file,
                $userRepo,
                $petrinetRepo
            );
            $response->getBody()->write($view->render());
            return $response->withHeader("Content-type", $mediaType)
                            ->withStatus(201);
        } catch (UserNotFoundException $e) {
            return $this->fail($request, $response, $e, 404);
        }
    }

    protected function getViewFactory(): \Cora\Views\AbstractViewFactory {
        return new PetrinetCreatedViewFactory();
    }
}
