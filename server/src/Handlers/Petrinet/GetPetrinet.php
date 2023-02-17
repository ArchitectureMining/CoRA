<?php

namespace Cora\Handlers\Petrinet;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

use Cora\Handlers\AbstractRequestHandler;
use Cora\Services\GetPetrinetService;

class GetPetrinet extends AbstractRequestHandler {
    public function handleRequest(Request $request, Response $response, $args) {
        $params = $request->getQueryParams();
        $petrinetId = $args["petrinet_id"];
        $markingId  = $params["marking_id"] ?? NULL;

        if (is_null($petrinetId)) {
            throw new HttpBadRequestException(
                $request, 'No petrinet id specified');
        }

        $service = $this->container->get(GetPetrinetService::class);
        $petrinet = $service->get($petrinetId, $markingId);

        if (is_null($petrinet)) {
            throw new HttpNotFoundException(
                $request, 'Could not find petri net');
        }

        $response->getBody()->write(json_encode($petrinet));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
