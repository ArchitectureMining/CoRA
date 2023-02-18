<?php

namespace Cora\Views\Json;

use Cora\Views\UserViewInterface;
use Cora\Domain\User\User;

class UserView implements UserViewInterface {
    protected $user;

    public function getUser(): User {
        return $this->user;
    }

    public function setUser(User $user): void {
        $this->user = $user;
    }

    public function render(): string {
        return json_encode($this->getUser());
    }
}
