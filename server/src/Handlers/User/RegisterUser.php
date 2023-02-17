<?php

namespace Cora\Handlers\User;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Slim\Exception\HttpBadRequestException;

use Cora\Handlers\AbstractRequestHandler;
use Cora\Services\RegisterUserService;

class RegisterUser extends AbstractRequestHandler {
    public function handleRequest(Request $request, Response $response, $args) {
        $body = $request->getParsedBody();

        if (!isset($body['name']))
            throw new HttpBadRequestException($request, "No name supplied");

        $service = $this->container->get(RegisterUserService::class);
        $registration = $service->register($body['name']);

        if ($registration->failed()) {
            throw new HttpBadRequestException(
                $request, $registration->getErrorMessage());
        }

        $data = ["user_id" => $registration->getUserId()];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
