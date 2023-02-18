<?php

namespace Cora\Views\Factory;

use Cora\Views\JsonUserView;

class UserViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "application/json" => JsonUserView::class
        ];
    }
}
