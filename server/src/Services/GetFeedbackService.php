<?php

namespace Cora\Services;

use Cora\Converters\JsonToGraph;
use Cora\Domain\Petrinet\MarkedPetrinet;
use Cora\Domain\Petrinet\PetrinetRepository as PetriRepo;
use Cora\Domain\Session\SessionRepository as SessionRepo;
use Cora\Domain\User\UserRepository as UserRepo;
use Cora\Domain\Feedback\View\FeedbackViewInterface as View;
use Cora\SystemCheckers\CheckCoverabilityGraph;

use Exception;

class GetFeedbackService {
    public function get(
        View &$view,
        $jsonGraph,
        $userId,
        $petrinetId,
        $sessionId,
        UserRepo $userRepo,
        PetriRepo $petriRepo,
        SessionRepo $sessionRepo)
    {
        $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
        $petrinetId = filter_var($petrinetId, FILTER_SANITIZE_NUMBER_INT);
        $sessionId = filter_var($sessionId, FILTER_SANITIZE_NUMBER_INT);

        if (!$userRepo->userExists("id", $userId))
            throw new Exception("User does not exist");
        if (!$petriRepo->petrinetExists($petrinetId))
            throw new Exception("Petri net does not exist");
        $markings = $petriRepo->getMarkings($petrinetId);
        if (empty($markings))
            throw new Exception("Could not provide feedback: no initial marking");
        $petrinet = $petriRepo->getPetrinet($petrinetId);
        $marking = $petriRepo->getMarking($markings[0]["id"], $petrinet);
        $marked = new MarkedPetrinet($petrinet, $marking);
        $converter = new JsonToGraph($jsonGraph, $petrinet);
        $graph = $converter->convert();
        $checker = new CheckCoverabilityGraph($graph, $marked);
        $feedback = $checker->check();
        $sessionRepo->appendGraph($userId, $sessionId, $petrinetId, $graph);
        $view->setFeedback($feedback);
    }
}
