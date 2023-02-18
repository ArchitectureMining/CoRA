<?php

namespace Cora\Views\Factory;

interface ViewFactory {
    public function create(string $mediaType);
    public function getContentTypes();
}
