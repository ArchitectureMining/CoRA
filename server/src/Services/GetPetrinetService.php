<?php

namespace Cora\Services;

use Cora\Domain\Systems\Petrinet\MarkedPetrinet;
use Cora\Repositories\PetrinetRepository as PetriRepo;

use Exception;

class GetPetrinetService {
    public function get($id, PetriRepo $petriRepo) {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $petrinet = $petriRepo->getPetrinet($id);
        if (is_null($petrinet))
            throw new Exception("The Petri net could not be found");
        $markings = $petriRepo->getMarkings($id);
        if (!empty($markings)) {
            $marking = $petriRepo->getMarking($markings[0]["id"], $petrinet);
            $marked = new MarkedPetrinet($petrinet, $marking);
            return $marked;
        }
        return $petrinet;
    }
}