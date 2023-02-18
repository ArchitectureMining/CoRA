<?php

namespace Cora\Views\Factory;

use Cora\Views\JsonPetrinetView;

class PetrinetViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "application/json" => JsonPetrinetView::class
        ];
    }
}
