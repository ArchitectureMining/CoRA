<?php

namespace Cora\Views\Factory;

use Cora\Views\Svg;

class PetrinetImageViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "image/svg+xml" => Svg\PetrinetImageView::class
        ];
    }
}
