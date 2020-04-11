<?php

namespace Cora\Domain\Systems\Petrinet;

use IteratorAggregate;
use JsonSerializable;

interface TransitionContainerInterface extends JsonSerializable, IteratorAggregate {
    public function contains(Transition $t): bool;
    public function add(Transition $t): void;
}