<?php

namespace Application\Controller;

use Application\Service\Synchro\SynchroServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SynchroController extends AbstractActionController {
    use SynchroServiceAwareTrait;

    public function indexAction()
    {
        return new ViewModel();
    }
}