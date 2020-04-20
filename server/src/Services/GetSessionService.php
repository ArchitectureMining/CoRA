<?php

namespace Cora\Services;

use Cora\Repositories\SessionRepository as SessionRepo;
use Cora\Domain\User\UserRepository as UserRepo;
use Cora\Domain\User\UserNotFoundException;
use Cora\Domain\Session\NoSessionException;
use Cora\Views\CurrentSessionViewInterface as View;

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
        if ($session === FALSE)
            throw new NoSessionException("A session for this user has not yet been created");
        $view->setSessionId($session);
    }
}
