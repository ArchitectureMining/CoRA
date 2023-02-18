<?php

namespace Cora\Handlers\Petrinet;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

use Cora\Handlers\AbstractHandler;
use Cora\Services\GetPetrinetService;
use Cora\Views\Factory\ViewFactory;
use Cora\Views\Factory\PetrinetViewFactory;

class GetPetrinet extends AbstractHandler {
    public function handle(Request $request, Response $response, $args) {
        $params = $request->getQueryParams();
        $petrinetId = $args["petrinet_id"];
        $markingId  = $params["marking_id"] ?? NULL;

        if (is_null($petrinetId))
            throw new HttpBadRequestException(
                $request, 'No petrinet id specified');

        $service = $this->container->get(GetPetrinetService::class);
        $petrinet = $service->get($petrinetId, $markingId);

        if (is_null($petrinet))
            throw new HttpNotFoundException(
                $request, 'Could not find petri net');

        $view = $this->getView();
        $view->setPetrinet($petrinet);
        $response->getBody()->write($view->render());
        return $response;
    }

    protected function getViewFactory(): ViewFactory {
        return new PetrinetViewFactory();
    }
}
