<?php

namespace Cora\Service;

use Psr\Http\Message\UploadedFileInterface as File;

use Cora\Converter\LolaToPetrinet;
use Cora\Converter\PetrinetTranslator;
use Cora\Repository\UserRepository;
use Cora\Repository\PetrinetRepository;

class RegisterPetrinetService {
    private $petrinetRepository;
    private $userRepository;

    public function __construct(PetrinetRepository $pr, UserRepository $ur) {
        $this->petrinetRepository = $pr;
        $this->userRepository = $ur;
    }

    public function register(File $file, $userId) {
        $error = $this->validate($file, $userId);
        if (!is_null($error))
            return new RegistrationResult(NULL, NULL, false, $error);

        $lola = $file->getStream()->getContents();
        $converter = new LolaToPetrinet($lola);
        $marked = $converter->convert();
        $translate = true;
        if ($translate) {
            $translator = new PetrinetTranslator($marked);
            $marked = $translator->convert();
        }
        $result = $this->petrinetRepository->saveMarkedPetrinet(
            $marked->getPetrinet(),
            $marked->getMarking(),
            $userId);
        $pid = $result->getPetrinetId();
        $mid = $result->getMarkingId();
        return new RegistrationResult($pid, $mid, true, '');
    }

    private function validate(File $file, $userId) {
        if (!$this->userRepository->userExists("id", $userId))
            return "No user with this id exists";

        $error = $file->getError();
        if ($error != UPLOAD_ERR_OK)
            return $message = FileUploadUtils::getErrorMessage($error);

        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        if ($extension != "lola")
             return "Only files with a lola extension are accepted";
    }
}

class RegistrationResult {
    private $petrinetId, $markingId, $success, $error;

    public function __construct($petrinetId, $markingId, $success, $error) {
        $this->petrinetId = $petrinetId;
        $this->markingId = $markingId;
        $this->success = $success;
        $this->error = $error;
    }

    public function isSuccess() {
        return $this->success;
    }

    public function isFailure() {
        return !$this->isSuccess();
    }

    public function getError() {
        return $this->error;
    }

    public function getPetrinetId() {
        return $this->petrinetId;
    }

    public function getMarkingId() {
        return $this->markingId;
    }
}
