<?php

namespace Cora\Handlers\User;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Cora\Domain\User\UserRepository as UserRepo;
use Cora\Domain\User\View\UsersViewFactory;
use Cora\Handlers\AbstractRequestHandler;
use Cora\Services\GetUsersService;

class GetUsers extends AbstractRequestHandler {
    const DEFAULT_PAGE = 1;
    const DEFAULT_LIMIT = MAX_USER_RESULT_SIZE;

    public function handle(Request $request, Response $response, $args) {
        $mediaType = $this->getMediaType($request);
        $repo      = $this->container->get(UserRepo::class);
        $view      = $this->getView($mediaType);
        $service   = $this->container->get(GetUsersService::class);
        $page      = $args["page"]  ?? self::DEFAULT_PAGE;
        $limit     = $args["limit"] ?? self::DEFAULT_LIMIT;

        $service->getUsers($view, $repo, $page, $limit);
        $response->getBody()->write($view->render());
        return $response->withHeader("Content-type", $mediaType)
                        ->withStatus(200);
    }

    protected function getViewFactory(): \Cora\Views\AbstractViewFactory {
        return new UsersViewFactory();
    }
}
