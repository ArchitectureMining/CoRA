<?php

namespace Cora\Views\Factory;

use Cora\Views\Json;

class PetrinetsViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "application/json" => Json\PetrinetsView::class
        ];
    }
}
