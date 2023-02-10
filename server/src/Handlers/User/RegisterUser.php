<?php

namespace Cora\Handlers\User;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cora\Domain\User\UserRepository as UserRepo;
use Cora\Domain\User\Exception\UserRegistrationException;
use Cora\Domain\User\View\UserCreatedViewFactory;
use Cora\Handlers\AbstractRequestHandler;
use Cora\Services\RegisterUserService;
use Cora\Views\AbstractViewFactory;

use Exception;

class RegisterUser extends AbstractRequestHandler {
    public function handle(Request $request, Response $response, $args) {
        $body = $request->getParsedBody();
        if (!isset($body["name"]))
            throw new Exception("No name supplied");
        try {
            $mediaType = $this->getMediaType($request);
            $repo      = $this->container->get(UserRepo::class);
            $view      = $this->getView($mediaType);
            $service   = $this->container->get(RegisterUserService::class);
            $service->register($view, $repo, $body["name"]);
            $response->getBody()->write($view->render());
            return $response->withHeader("Content-type", $mediaType)
                            ->withStatus(201);
        } catch (UserRegistrationException $e) {
            return $this->fail($request, $response, $e, 400);
        }
    }

    protected function getViewFactory(): AbstractViewFactory {
        return new UserCreatedViewFactory();
    }
}
