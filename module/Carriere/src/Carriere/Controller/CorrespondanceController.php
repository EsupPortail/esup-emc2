<?php

namespace Carriere\Controller;

use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CorrespondanceController extends AbstractActionController
{
    use CorrespondanceServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $correspondances = $this->getCorrespondanceService()->getCorrespondances();

        return new ViewModel([
            'correspondances' => $correspondances,
        ]);
    }

    public function afficherAgentsAction() : ViewModel
    {
        $correspondance = $this->getCorrespondanceService()->getRequestedCorrespondance($this);

        return new ViewModel([
            'title' => 'Agents ayant la correspondance ['. $correspondance->getLibelleCourt().']',
            'correspondance' => $correspondance,
        ]);
    }
}