<?php

namespace Cora\Handlers\User;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cora\Handlers\AbstractHandler;
use Cora\Services\GetUsersService;
use Cora\Views\Factory\ViewFactory;
use Cora\Views\Factory\UsersViewFactory;

class GetUsers extends AbstractHandler {
    public function handle(Request $request, Response $response, $args) {
        $page  = $args["page"]  ?? NULL;
        $limit = $args["limit"] ?? NULL;

        $service = $this->container->get(GetUsersService::class);
        $users = $service->getUsers($page, $limit);

        $view = $this->getView();
        $view->setUsers($users);
        $response->getBody()->write($view->render());
    }

    protected function getViewFactory(): ViewFactory {
        return new UsersViewFactory();
    }
}
