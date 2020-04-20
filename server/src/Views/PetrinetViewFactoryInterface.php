<?php

namespace Cora\Views;

interface PetrinetViewFactoryInterface {
    public function createPetrinetView(): PetrinetViewInterface;
    public function createPetrinetsView(): PetrinetsViewInterface;
    public function createPetrinetCreatedView(): PetrinetCreatedViewInterface;
}