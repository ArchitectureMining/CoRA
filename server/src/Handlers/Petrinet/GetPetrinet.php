<?php

namespace Cora\Handlers\Petrinet;

use Slim\Http\Request;
use Slim\Http\Response;

use Cora\Domain\Petrinet\PetrinetRepository as PetrinetRepo;
use Cora\Domain\Petrinet\PetrinetNotFoundException;
use Cora\Domain\Petrinet\View\MarkedPetrinetViewFactory;
use Cora\Handlers\AbstractRequestHandler;
use Cora\Services\GetPetrinetService;

class GetPetrinet extends AbstractRequestHandler {
    public function handle(Request $request, Response $response, $args) {
        try {
            $petriRepo = $this->container->get(PetrinetRepo::class);
            $mediaType = $this->getMediaType($request);
            $view      = $this->getView($mediaType);
            $service   = $this->container->get(GetPetrinetService::class);
            $markingId = $request->getParam("marking_id", NULL);
            $service->get($view, $args["petrinet_id"], $markingId, $petriRepo);
            return $response->withHeader("Content-type", $mediaType)
                            ->withStatus(200)
                            ->write($view->render());
        } catch (PetrinetNotFoundException $e) {
            return $this->fail($request, $response, $e, 404);
        }
    }

    protected function getViewFactory(): \Cora\Views\AbstractViewFactory {
        return new MarkedPetrinetViewFactory();
    }
}
