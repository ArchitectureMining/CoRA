<?php

namespace Cora\Views;

use Cora\Views\ViewInterface;

interface UsersViewInterface extends ViewInterface {
    public function getUsers(): array;
    public function setUsers(array $users): void;
}
