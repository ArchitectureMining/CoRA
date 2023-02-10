<?php

namespace Cora\Handlers\Petrinet;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cora\Domain\Petrinet\PetrinetRepository as PetrinetRepo;
use Cora\Domain\Petrinet\View\PetrinetsViewFactory;
use Cora\Handlers\AbstractRequestHandler;
use Cora\Services\GetPetrinetsService;

class GetPetrinets extends AbstractRequestHandler {
    const DEFAULT_LIMIT = MAX_PETRINET_RESULT_SIZE;
    const DEFAULT_PAGE  = 1;

    public function handle(Request $request, Response $response, $args) {
        $repo      = $this->container->get(PetrinetRepo::class);
        $service   = $this->container->get(GetPetrinetsService::class);
        $limit     = $args["limit"] ?? self::DEFAULT_LIMIT;
        $page      = $args["page"]  ?? self::DEFAULT_PAGE;
        $mediaType = $this->getMediaType($request);
        $view      = $this->getView($mediaType);
        $service->get($view, $page, $limit, $repo);
        $response->getBody()->write($view->render());
        return $response->withHeader("Content-type", $mediaType)
                        ->withStatus(200);
    }

    protected function getViewFactory(): \Cora\Views\AbstractViewFactory {
        return new PetrinetsViewFactory();
    }
}
