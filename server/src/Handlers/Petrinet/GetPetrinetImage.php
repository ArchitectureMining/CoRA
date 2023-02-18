<?php

namespace Cora\Handlers\Petrinet;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cora\Handlers\AbstractHandler;
use Cora\Services\GetPetrinetImageService;
use Cora\Views\Factory\ViewFactory;
use Cora\Views\Factory\PetrinetImageViewFactory;

class GetPetrinetImage extends AbstractHandler {
    public function handle(Request $request, Response $response, $args) {
        $queryParams = $request->getQueryParams();

        $pid     = $args["petrinet_id"];
        $mid     = $queryParams["marking_id"] ?? NULL;
        $service = $this->container->get(GetPetrinetImageService::class);
        $image   = $service->get($pid, $mid);

        $view = $this->getView();
        $view->setData($image);
        $response->getBody()->write($view->render());
        return $response;
    }

    protected function getViewFactory(): ViewFactory {
        return new PetrinetImageViewFactory();
    }
}
