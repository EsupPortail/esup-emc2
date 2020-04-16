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
        $jobs = $this->getSynchroService()->getSynchroJobs();

        $synchro = $this->getSynchroService()->getSynchroJob('structure-type');
        $this->getSynchroService()->synchronize($synchro);

//        $this->getSynchroService()->synchrStructureType();
        return new ViewModel([
            'jobs' => $jobs,
            'synchro' => $synchro,
        ]);
    }
}