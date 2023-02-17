<?php

namespace Cora\Handlers\User;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cora\Handlers\AbstractRequestHandler;
use Cora\Services\GetUsersService;

class GetUsers extends AbstractRequestHandler {
    public function handleRequest(Request $request, Response $response, $args) {
        $page  = $args["page"]  ?? NULL;
        $limit = $args["limit"] ?? NULL;

        $service = $this->container->get(GetUsersService::class);
        $users = $service->getUsers($page, $limit);

        $response->getBody()->write(json_encode($users));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
