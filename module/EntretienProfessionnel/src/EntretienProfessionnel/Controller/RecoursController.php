<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Form\Recours\RecoursFormAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Recours\RecoursServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Unicaen\Console\View\ViewModel;

class RecoursController extends AbstractActionController
{
    use EntretienProfessionnelServiceAwareTrait;
    use RecoursServiceAwareTrait;
    use RecoursFormAwareTrait;

    public function ajouterAction() : ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this);

        return new ViewModel([]);
    }
}