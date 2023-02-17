<?php

namespace Cora\Handlers\Petrinet;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cora\Handlers\AbstractRequestHandler;
use Cora\Domain\Petrinet\PetrinetNotFoundException;
use Cora\Services\GetPetrinetImageService;

class GetPetrinetImage extends AbstractRequestHandler {
    public function handleRequest(Request $request, Response $response, $args) {
        try {
            $queryParams = $request->getQueryParams();

            $pid     = $args["petrinet_id"];
            $mid     = $queryParams["marking_id"] ?? NULL;
            $service = $this->container->get(GetPetrinetImageService::class);
            $image   = $service->get($pid, $mid);

            $response->getBody()->write($image);
            return $response->withHeader("Content-Type", "image/svg+xml");
        } catch(PetrinetNotFoundException $e) {
            $message = "Petri net with id $pid not found";
            throw new HttpNotFoundException($request, $message, $e);
        }
    }
}
