<?php

namespace Cora\Views\Factory;

use Cora\Views\Json;

class PetrinetCreatedViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "application/json" => Json\PetrinetCreatedView::class
        ];
    }
}
