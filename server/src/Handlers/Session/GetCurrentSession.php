<?php

namespace Cora\Handlers\Session;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

use Cora\Handlers\AbstractHandler;
use Cora\Services\GetSessionService;
use Cora\Views\Factory\ViewFactory;
use Cora\Views\Factory\CurrentSessionViewFactory;

class GetCurrentSession extends AbstractHandler {
    public function handle(Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
        $userId = $parsedBody["user_id"] ?? NULL;
        if (is_null($userId))
            throw new HttpNotFoundException($request, "No user id supplied");

        $service = $this->container->get(GetSessionService::class);
        $result = $service->get($userId);

        if (is_null($result))
            throw new HttpNotFoundException($request, "No session found");

        $view = $this->getView();
        $view->setSession($result);
        $response->getBody()->write($view->render());
        return $response;
    }

    protected function getViewFactory(): ViewFactory {
        return new CurrentSessionViewFactory();
    }
}
