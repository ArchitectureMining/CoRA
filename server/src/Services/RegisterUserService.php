<?php

namespace Cora\Services;

use Cora\Domain\User\Validation\UniqueUserRule;
use Cora\Domain\User\UserRepository as UserRepo;
use Cora\Domain\User\Exception\UserRegistrationException;
use Cora\Validation\MaxLengthRule;
use Cora\Validation\MinLengthRule;
use Cora\Validation\RegexRule;
use Cora\Validation\RuleValidator;
use Cora\Domain\User\View\UserCreatedViewInterface as View;

class RegisterUserService {
    public function register(View &$view, UserRepo $repo, $name) {
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $validator = $this->getValidator($repo);
        if (!$validator->validate($name))
            throw new UserRegistrationException($validator->getError());
        $id = $repo->saveUser($name);
        $view->setUserId($id);
    }

    protected function getValidator(UserRepo $repo) {
        $minRule = new MinLengthRule(
            4,
            "Your username is too short. A minimum of four characters is rquired");
        $maxRule = new MaxLengthRule(
            20,
            "Your username is too long. You may use up to twenty characters");
        $regexRule = new RegexRule(
            "/^\w+$/",
            "Your username contains illegal characters");
        $uniquenessRule = new UniqueUserRule($repo);
        $validator = new RuleValidator([
            $minRule, $maxRule, $regexRule, $uniquenessRule
        ]);
        return $validator;
    }
}
