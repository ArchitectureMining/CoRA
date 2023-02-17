<?php

namespace Cora\Services;

use Cora\Repositories\SessionRepository;
use Cora\Repositories\UserRepository;

class GetSessionService {
    private $sessionRepository, $userRepository;

    public function __construct(SessionRepository $sr, UserRepository $ur) {
        $this->sessionRepository = $sr;
        $this->userRepository = $ur;
    }

    public function get($uid) {
        $id = filter_var($uid, FILTER_SANITIZE_NUMBER_INT);
        if (!$this->userRepository->userExists("id", $uid))
            return NULL;
        $session = $this->sessionRepository->getCurrentSession($id);
        return $session;
    }
}
