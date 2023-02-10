<?php

namespace Cora\Handlers\User;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cora\Domain\User\UserRepository as UserRepo;
use Cora\Domain\User\Exception\UserNotFoundException;
use Cora\Domain\User\View\UserViewFactory;
use Cora\Handlers\AbstractRequestHandler;
use Cora\Services\GetUserService;
use Exception;

class GetUser extends AbstractRequestHandler {
    public function handle(Request $request, Response $response, $args) {
        $id = $args["id"];
        if (!isset($id))
            throw new Exception("No id given");
        try {
            $mediaType = $this->getMediaType($request);
            $view      = $this->getView($mediaType);
            $repo      = $this->container->get(UserRepo::class);
            $service   = $this->container->get(GetUserService::class);
            $service->getUser($view, $repo, $id);
            $response->getBody()->write($view->render());
            return $response->withHeader("Content-type", $mediaType);
        } catch (UserNotFoundException $e) {
            return $this->fail($request, $response, $e, 404);
        }
    }

    protected function getViewFactory(): \Cora\Views\AbstractViewFactory {
        return new UserViewFactory();
    }
}
