<?php

namespace Cora\Views\Factory;

use Cora\Views\JsonSessionCreatedView;

class SessionCreatedViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "application/json" => JsonSessionCreatedView::class
        ];
    }
}
