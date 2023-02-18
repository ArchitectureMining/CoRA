<?php

namespace Cora\Views\Factory;

use Cora\Views\JsonPetrinetCreatedView;

class PetrinetCreatedViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "application/json" => JsonPetrinetCreatedView::class
        ];
    }
}
