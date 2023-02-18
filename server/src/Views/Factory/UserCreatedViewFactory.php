<?php

namespace Cora\Views\Factory;

use Cora\Views\Json;

class UserCreatedViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "application/json" => Json\UserCreatedView::class
        ];
    }
}
