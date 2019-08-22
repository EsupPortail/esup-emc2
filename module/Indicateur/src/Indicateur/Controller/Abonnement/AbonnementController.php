<?php

namespace Indicateur\Controller\Abonnement;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AbonnementController extends AbstractActionController {

    public function indexAction()
    {
        $abonnements = [];

        return new ViewModel([
            'abonnements' => $abonnements,
        ]);
    }
}