<?php

namespace Cora\Handlers\Petrinet;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

use Cora\Handlers\AbstractRequestHandler;
use Cora\Services\RegisterPetrinetService;

class RegisterPetrinet extends AbstractRequestHandler {
    public function handleRequest(Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();

        $userId = $parsedBody["user_id"] ?? NULL;
        if (is_null($userId))
            throw new HttpBadRequestException($request, "No user id supplied");
        $files = $request->getUploadedFiles();
        if (!isset($files["petrinet"]))
            throw new HttpBadRequestException($request, "No Petri net uploaded");
        $file    = $files["petrinet"];

        $service = $this->container->get(RegisterPetrinetService::class);
        $result  = $service->register($file, $userId);

        if ($result->isFailure())
            throw new HttpBadRequestException($request, $result->getError());

        $ids = ["petrinet_id" => $result->getPetrinetId(),
                "marking_id" => $result->getMarkingId()];

        $response->getBody()->write(json_encode($ids));
        return $response->withHeader("Content-Type", "application/json")
                        ->withStatus(201);
    }
}
