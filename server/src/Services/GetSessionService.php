<?php

namespace Cora\Services;

use Cora\Domain\Session\SessionRepository as SessionRepo;
use Cora\Domain\User\UserRepository as UserRepo;
use Cora\Domain\User\Exception\UserNotFoundException;
use Cora\Domain\Session\View\CurrentSessionViewInterface as View;

class GetSessionService {
    public function get(
        View &$view,
        $uid, SessionRepo
        $sessionRepo,
        UserRepo $userRepo)
    {
        $id = filter_var($uid, FILTER_SANITIZE_NUMBER_INT);
        if (!$userRepo->userExists("id", $uid))
            throw new UserNotFoundException("This user does not exist");
        $session = $sessionRepo->getCurrentSession($id);
        $view->setSessionId($session->getId());
    }
}
