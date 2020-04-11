<?php

namespace Cora\Domain\Systems\Petrinet;

use Cora\Domain\Systems\Petrinet\PetrinetInterface as Petrinet;
use Cora\Domain\Systems\Petrinet\FlowInterface as Flow;

use Exception;

class PetrinetBuilder implements PetrinetBuilderInterface {
    protected $places;
    protected $transitions;
    protected $flows;

    public function __construct() {
        $this->places = new PlaceContainer();
        $this->transitions = new TransitionContainer();
        $this->flows = new FlowMap();
    }

    public function addPlace(Place $p): void {
        $this->places->add($p);
    }

    public function addTransition(Transition $t): void {
        $this->transitions->add($t);
    }

    public function addFlow(Flow $f, int $w): void {
        $this->flows->add($f, $w);
    }

    public function getPetrinet(): Petrinet {
        $flowMap = $this->flows;
        $places = $this->places;
        $transitions = $this->transitions;
        foreach($flowMap->flows() as $flow) {
            $from = $flow->getFrom();
            $to = $flow->getTo();
            if (!($from instanceof Place && $to instanceof Transition &&
                  $this->places->contains($from) && $this->transitions->contains($to)) &&
                !($from instanceof Transition && $to instanceof Place &&
                  $this->transitions->contains($from) && $this->places->contains($to)))
                throw new Exception(
                    "At least one flow has an element that has not been added " .
                    "to the transitions or places"
                );
        }
        $petrinet = new Petrinet2($places, $transitions, $flowMap);
        return $petrinet;
    }

    public function hasPlace(string $place): bool {
        $place = new Place($place);
        return $this->places->contains($place);
    }

    public function hasTransition(string $transition): bool {
        $trans = new Transition($transition);
        return $this->transitions->contains($trans);
    }
}
