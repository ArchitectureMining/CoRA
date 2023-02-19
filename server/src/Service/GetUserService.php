<?php

namespace Cora\Service;

use Cora\Repositories\UserRepository;
use Cora\Exception\UserNotFoundException;

class GetUserService {
    protected $repository;

    public function __construct(UserRepository $repo) {
        $this->repository = $repo;
    }

    public function getUser($id) {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $user = $this->repository->getUser('id', $id);
        return $user;
    }
}
