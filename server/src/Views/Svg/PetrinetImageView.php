<?php

namespace Cora\Views\Svg;

use Cora\Views\PetrinetImageViewInterface;

class PetrinetImageView implements PetrinetImageViewInterface {
    private $data;

    public function getData(): string {
        return $this->data;
    }

    public function setData(string $data): void {
        $this->data = $data;
    }

    public function render(): string {
        return $this->getData();
    }
}
