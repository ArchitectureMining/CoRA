<?php

namespace Cora\Views\Factory;

interface ViewFactory {
    public function create(string $mediaType);
    public function getContentTypes();
}

// use Exception;

// class ViewFactory {
//     public function getUserViewFactory(string $ct) {
//         switch($ct) {
//         case "application/json":
//             return new JsonUserViewFactory();
//         default:
//             throw new Exception("Could not construct view");
//         }
//     }
// }
