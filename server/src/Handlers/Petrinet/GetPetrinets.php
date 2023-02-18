<?php

namespace Cora\Handlers\Petrinet;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cora\Handlers\AbstractHandler;
use Cora\Services\GetPetrinetsService;
use Cora\Views\Factory\ViewFactory;
use Cora\Views\Factory\PetrinetsViewFactory;

class GetPetrinets extends AbstractHandler {
    public function handle(Request $request, Response $response, $args) {
        $limit = $args["limit"] ?? NULL;
        $page  = $args["page"]  ?? NULL;
        $service = $this->container->get(GetPetrinetsService::class);

        $petrinets = $service->get($page, $limit);

        $view = $this->getView();
        $view->setPetrinets($petrinets);

        $response->getBody()->write($view->render());
        return $response;
    }

    protected function getViewFactory(): ViewFactory {
        return new PetrinetsViewFactory;
    }
}
