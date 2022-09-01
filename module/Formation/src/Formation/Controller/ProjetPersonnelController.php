<?php

namespace Formation\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ProjetPersonnelController extends  AbstractActionController {

    public function indexAction() : ViewModel
    {
        return new ViewModel([]);
    }
}