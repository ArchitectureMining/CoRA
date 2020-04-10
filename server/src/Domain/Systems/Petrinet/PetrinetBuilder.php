<?php

namespace Cora\Domain\Systems\Petrinet;

use Cora\Domain\Systems\Petrinet\PetrinetInterface as Petrinet;
use Cora\Domain\Systems\Petrinet\PetrinetElementInterface as Element;
use Cora\Domain\Systems\Petrinet\PetrinetElementType as ElementType;
use Cora\Domain\Systems\Petrinet\FlowInterface as Flow;
use Cora\Domain\Systems\MarkingInterface as Marking;

use Cora\Domain\Systems\Petrinet\PetrinetElementContainer as Container;
use Cora\Domain\Systems\Petrinet\FlowMap as Map;
use Exception;

class PetrinetBuilder implements PetrinetBuilderInterface {
    protected $places;
    protected $transitions;
    protected $flows;
    protected $initial;

    public function __construct() {
        $this->places = new Container();
        $this->transitions = new Container();
        $this->flows = new Map();
    }

    public function addElement(Element $e): void {
        if ($e->getType() == ElementType::TRANSITION) {
            $this->transitions->add($e);
        } else if ($e->getType() == ElementType::PLACE) {
            $this->places->add($e);
        } else {
            throw new Exception("Invalid element type added");
        }
    }

    public function addFlow(Flow $f, $w): void {
        $this->flows->add($f, $w);
    }

    public function setInitial(Marking $m): void {
        $this->initial = $m;
    }

    public function getPetrinet(): Petrinet {
        $flows = $this->flows;
        $places = $this->places;
        $transitions = $this->transitions;
        foreach($flows as $flow => $weight) {
            $from = $flow->getFrom();
            $to = $flow->getTo();
            if (!(($places->has($from) || $transitions->has($from)) &&
                  ($places->has($to)   || $transitions->has($to))))
                throw new Exception(
                    "At least one flow has an element that has not been added " .
                    "to the transitions or places"
                );
        }
        $petrinet = new Petrinet2($places, $transitions, $flows);
        return $petrinet;
    }
}