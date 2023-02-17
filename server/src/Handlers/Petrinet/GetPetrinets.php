<?php

namespace Cora\Handlers\Petrinet;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cora\Domain\Petrinet\PetrinetRepository as PetrinetRepo;
use Cora\Domain\Petrinet\View\PetrinetsViewFactory;
use Cora\Handlers\AbstractRequestHandler;
use Cora\Services\GetPetrinetsService;

class GetPetrinets extends AbstractRequestHandler {
    public function handleRequest(Request $request, Response $response, $args) {
        $limit = $args["limit"] ?? NULL;
        $page  = $args["page"]  ?? NULL;
        $service = $this->container->get(GetPetrinetsService::class);

        $petrinets = $service->get($page, $limit);
        $response->getBody()->write(json_encode($petrinets));
        return $response->withHeader('Content-Type', 'application/json');
    }

    protected function getViewFactory(): \Cora\Views\AbstractViewFactory {
        return new PetrinetsViewFactory();
    }
}
