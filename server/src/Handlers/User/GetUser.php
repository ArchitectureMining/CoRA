<?php

namespace Cora\Handlers\User;

use Cora\Handlers\AbstractRequestHandler;
use Cora\Repositories\UserRepository;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class GetUser extends AbstractRequestHandler {
    public function handleRequest(Request $request, Response $response, $args) {
        $id = $args['id'];
        if (!isset($id))
            throw new HttpBadRequestException($request, 'No id given');

        $repo = $this->container->get(UserRepository::class);
        $user = $repo->getUser('id', $id);

        if (is_null($user)) throw new HttpNotFoundException(
            $request, "No user found for this id");

        $response->getBody()->write(json_encode($user));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
