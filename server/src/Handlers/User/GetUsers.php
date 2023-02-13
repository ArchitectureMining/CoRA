<?php

namespace Cora\Handlers\User;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cora\Handlers\AbstractRequestHandler;
use Cora\Services\GetUsersService;

class GetUsers extends AbstractRequestHandler {
    const DEFAULT_PAGE = 1;
    const DEFAULT_LIMIT = MAX_USER_RESULT_SIZE;

    public function handle(Request $request, Response $response, $args) {
        $page      = $args["page"]  ?? self::DEFAULT_PAGE;
        $limit     = $args["limit"] ?? self::DEFAULT_LIMIT;

        $service = $this->container->get(GetUsersService::class);
        $users = $service->getUsers($page, $limit);

        $response->getBody()->write(json_encode($users));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
