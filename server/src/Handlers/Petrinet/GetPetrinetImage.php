<?php

namespace Cora\Handlers\Petrinet;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cora\Handlers\AbstractRequestHandler;

use Cora\Domain\Petrinet\PetrinetNotFoundException;
use Cora\Domain\Petrinet\PetrinetRepository as PetrinetRepo;
use Cora\Domain\Petrinet\View\PetrinetImageViewFactory;
use Cora\Services\GetPetrinetImageService;

class GetPetrinetImage extends AbstractRequestHandler {
    public function handle(Request $request, Response $response, $args) {
        try {
            $queryParams = $request->getQueryParams();

            $pid       = $args["petrinet_id"];
            $mid       = $queryParams["marking_id"] ?? NULL;
            $repo      = $this->container->get(PetrinetRepo::class);
            $service   = $this->container->get(GetPetrinetImageService::class);
            $mediaType = $this->getMediaType($request);
            $view      = $this->getView($mediaType);
            $service->get($view, $pid, $mid, $repo);
            $response->getBody()->write($view->render());
            return $response->withHeader("Content-type", $mediaType)
                            ->withStatus(200);
        } catch (PetrinetNotFoundException $e) {
            return $this->fail($request, $response, $e, 404);
        }
    }

    protected function getViewFactory(): \Cora\Views\AbstractViewFactory {
        return new PetrinetImageViewFactory();
    }
}
