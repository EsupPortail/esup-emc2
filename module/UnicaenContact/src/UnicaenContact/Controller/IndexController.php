<?php

namespace UnicaenContact\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    public function indexAction(): ViewModel
    {
        return new ViewModel([]);
    }
}