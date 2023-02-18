<?php

namespace Cora\Views\Factory;

use Cora\Views\JsonPetrinetsView;

class PetrinetsViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "application/json" => JsonPetrinetsView::class
        ];
    }
}
