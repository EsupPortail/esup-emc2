<?php

namespace Indicateur\Controller\Indicateur;

use Indicateur\Service\Indicateur\IndicateurServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndicateurController extends AbstractActionController {
    use IndicateurServiceAwareTrait;

    public function indexAction()
    {
        $indicateurs = $this->getIndicateurService()->getIndicateurs();

        return new ViewModel([
            'indicateurs' => $indicateurs,
        ]);
    }

    public function afficherAction()
    {
        $indicateur = $this->getIndicateurService()->getRequestedIndicateur($this);
        $data = $this->getIndicateurService()->fetch($indicateur);

        return new ViewModel([
            'indicateur' => $indicateur,
            'data' => $data,
        ]);
    }

    public function rafraichirAction()
    {
        $indicateur = $this->getIndicateurService()->getRequestedIndicateur($this);
        $this->getIndicateurService()->refresh($indicateur);
        return $this->redirect()->toRoute('indicateur/afficher', ['indicateur' => $indicateur->getId()], [], true);
    }
}