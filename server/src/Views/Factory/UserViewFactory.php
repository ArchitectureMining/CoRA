<?php

namespace Cora\Views\Factory;

use Cora\Views\Json;

class UserViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "application/json" => Json\UserView::class
        ];
    }
}
