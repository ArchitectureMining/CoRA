<?php

namespace Cora\Views\Factory;

use Cora\Views\JsonFeedbackView;

class FeedbackViewFactory extends AbstractViewFactory {
    protected function getMediaAssociations(): array {
        return [
            "application/json" => JsonFeedbackView::class
        ];
    }
}
