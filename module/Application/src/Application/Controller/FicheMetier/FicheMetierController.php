<?php

namespace Application\Controller\FicheMetier;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FicheMetierController extends AbstractActionController{

    public function indexAction() {
        return new ViewModel();
    }

    public function afficherAction() {
        return new ViewModel();
    }
}