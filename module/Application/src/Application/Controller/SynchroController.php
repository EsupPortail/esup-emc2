<?php

namespace Application\Controller;

use Application\Service\Synchro\SynchroServiceAwareTrait;
use SimpleXMLElement;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SynchroController extends AbstractActionController {
    use SynchroServiceAwareTrait;

    public function indexAction()
    {
        $this->getSynchroService()->synchrStructureType();
        return new ViewModel([

        ]);
    }
}