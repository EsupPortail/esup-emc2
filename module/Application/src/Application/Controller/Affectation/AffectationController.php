<?php

namespace Application\Controller\Affectation;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AffectationController extends AbstractActionController {

    public function indexAction()
    {
        return new ViewModel();
    }
}