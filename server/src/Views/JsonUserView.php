<?php

namespace Cora\Views;

use Cora\Views\UserViewInterface as IUserView;
use Cora\Domain\User\User;

class JsonUserView implements IUserView {
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
