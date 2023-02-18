<?php

namespace Cora\Views\Factory;

use Cora\Views\SvgImageView;

class PetrinetImageViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "image/svg+xml" => SvgImageView::class
        ];
    }
}
