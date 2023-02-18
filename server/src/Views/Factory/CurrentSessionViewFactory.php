<?php

namespace Cora\Views\Factory;

use Cora\Views\JsonCurrentSessionView;

class CurrentSessionViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "application/json" => JsonCurrentSessionView::class
        ];
    }
}
