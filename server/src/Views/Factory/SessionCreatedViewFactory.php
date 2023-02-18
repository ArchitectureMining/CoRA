<?php

namespace Cora\Views\Factory;

use Cora\Views\Json;

class SessionCreatedViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "application/json" => Json\SessionCreatedView::class
        ];
    }
}
