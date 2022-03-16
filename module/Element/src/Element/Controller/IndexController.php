<?php

namespace Element\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

    public function indexAction() : ViewModel
    {
        return new ViewModel();
    }
}