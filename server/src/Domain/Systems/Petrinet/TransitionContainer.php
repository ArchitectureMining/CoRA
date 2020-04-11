<?php

namespace Cora\Domain\Systems\Petrinet;

use Ds\Set;

class TransitionContainer implements TransitionContainerInterface {
    protected $set;

    public function __construct() {
        $this->set = new Set();
    }

    public function contains(Transition $t): bool {
        return $this->set->contains($t);
    }

    public function add(Transition $t): void {
        $this->set->add($t);
    }

    public function jsonSerialize() {
        return $this->set;
    }

    public function getIterator() {
        return $this->set->getIterator();
    }
}