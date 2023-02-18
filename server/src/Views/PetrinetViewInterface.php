<?php

namespace Cora\Views;

use Cora\Domain\Petrinet\MarkedPetrinetInterface as IMarkedPetrinet;

interface PetrinetViewInterface extends ViewInterface {
    public function setPetrinet(IMarkedPetrinet $petrinet): void;
    public function getPetrinet(): IMarkedPetrinet;
}
