<?php

namespace Cora\Services;

use Cora\Repositories\SessionRepository as SessionRepo;
use Cora\Repositories\PetrinetRepository as PetriRepo;
use Cora\Repositories\UserRepository as UserRepo;

use Cora\Domain\Petrinet\PetrinetNotFoundException;
use Cora\Domain\User\Exception\UserNotFoundException;
use Cora\Domain\Petrinet\Marking\MarkingNotFoundException;

class StartSessionService {
    private $userRepository, $petrinetRepository, $sessionRepository;

    public function __construct(SessionRepo $sr, PetriRepo $pr, UserRepo $ur) {
        $this->userRepository = $ur;
        $this->petrinetRepository = $pr;
        $this->sessionRepository = $sr;
    }

    public function start($uid, $pid, $mid) {
        $uid = filter_var($uid, FILTER_SANITIZE_NUMBER_INT);
        $pid = filter_var($pid, FILTER_SANITIZE_NUMBER_INT);
        $mid = filter_var($mid, FILTER_SANITIZE_NUMBER_INT);

        if (!$this->userRepository->userExists("id", $uid))
            throw new UserNotFoundException(
                "Could not start session: user does not exist");
        if (!$this->petrinetRepository->petrinetExists($pid))
            throw new PetrinetNotFoundException(
                "Could not start session: Petri net does not exist");
        if (!$this->petrinetRepository->markingExists($mid))
            throw new MarkingNotFoundException(
                "Could not start session: Marking does not exist");

        return $this->sessionRepository->createNewSession($uid, $pid, $mid);
    }
}
