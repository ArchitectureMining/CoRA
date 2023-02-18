<?php

namespace Cora\Views\Factory;

use Cora\Views\Json;

class FeedbackViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "application/json" => Json\FeedbackView::class
        ];
    }
}
