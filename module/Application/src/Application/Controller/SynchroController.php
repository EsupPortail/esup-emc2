<?php

namespace Application\Controller;

use Application\Service\Synchro\SynchroServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

/** @method FlashMessenger flashMessenger() */

class SynchroController extends AbstractActionController {
    use SynchroServiceAwareTrait;

    public function indexAction()
    {
        $jobs = $this->getSynchroService()->getSynchroJobs();
        return new ViewModel([
            'jobs' => $jobs,
        ]);
    }

    public function synchroniserAction() {
        $key = $this->params()->fromRoute('key');
        $synchro = $this->getSynchroService()->getSynchroJob($key);

        $log = $this->getSynchroService()->synchronize($synchro);
        $this->flashMessenger()->addSuccessMessage($log->__toString());

        return $this->redirect()->toRoute('synchro', [], [], true);
    }
}