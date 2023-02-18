<?php

namespace Cora\Views\Factory;

use Cora\Views\JsonUsersView;

class UsersViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "application/json" => JsonUsersView::class
        ];
    }
}
