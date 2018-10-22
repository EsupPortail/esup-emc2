<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FicheMetierController extends AbstractActionController{

    public function indexAction() {

        var_dump(1234);
        return new ViewModel();
    }

    public function afficherAction() {
        return new ViewModel();
    }
}