<?php

namespace Cora\Views\Factory;

use Cora\Views\Json;

class PetrinetViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "application/json" => Json\PetrinetView::class
        ];
    }
}
