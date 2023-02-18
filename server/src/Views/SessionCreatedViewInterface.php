<?php

namespace Cora\Views;

use Cora\Views\ViewInterface;
use Cora\Domain\Session\Session;

interface SessionCreatedViewInterface extends ViewInterface {
    public function getSession(): Session;
    public function setSession(Session $session): void;
}
