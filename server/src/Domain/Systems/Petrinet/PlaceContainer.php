<?php

namespace Cora\Domain\Systems\Petrinet;

use Ds\Set;

class PlaceContainer implements PlaceContainerInterface {
    protected $set;

    public function __construct() {
        $this->set = new Set();
    }

    public function contains(Place $p): bool {
        return $this->set->contains($p);
    }

    public function add(Place $p): void {
        $this->set->add($p);
    }

    public function jsonSerialize() {
        return $this->set;
    }

    public function getIterator() {
        return $this->set->getIterator();
    }

    public function toArray(): array {
        return $this->set->toArray();
    }
}
