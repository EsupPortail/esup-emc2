<?php

namespace UnicaenDocument\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ContenuController extends AbstractActionController {

    public function indexAction()
    {
        return new ViewModel();
    }
}