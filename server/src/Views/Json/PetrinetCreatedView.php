<?php

namespace Cora\Views\Json;

use Cora\Views\PetrinetCreatedViewInterface;

class PetrinetCreatedView implements PetrinetCreatedViewInterface {
    protected $result;

    public function setResult($result) {
        $this->result = $result;
    }

    public function getResult() {
        return $this->result;
    }

    public function render(): string {
        $result = $this->getResult();
        return json_encode([
            "petrinet_id" => $result->getPetrinetId(),
            "marking_id" => $result->getMarkingId()
        ]);
    }
}