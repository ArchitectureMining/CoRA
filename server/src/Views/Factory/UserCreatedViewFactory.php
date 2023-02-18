<?php

namespace Cora\Views\Factory;

use Cora\Views\JsonUserCreatedView;

class UserCreatedViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "application/json" => JsonUserCreatedView::class
        ];
    }
}
