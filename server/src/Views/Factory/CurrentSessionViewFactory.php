<?php

namespace Cora\Views\Factory;

use Cora\Views\Json;

class CurrentSessionViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "application/json" => Json\CurrentSessionView::class
        ];
    }
}
