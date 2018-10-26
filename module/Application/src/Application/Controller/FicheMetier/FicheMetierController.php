<?php

namespace Application\Controller\FicheMetier;

use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FicheMetierController extends AbstractActionController
{
    use FicheMetierServiceAwareTrait;

    public function indexAction() {

        $fichesMetiers = $this->getFicheMetierService()->getFichesMetiers();

        return new ViewModel([
            'fichesMetiers' => $fichesMetiers,
        ]);
    }

    public function afficherAction() {
        return new ViewModel();
    }

    public function historiserAction()
    {
        $ficheId = $this->params()->fromRoute('id');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);

        if ($fiche === null) throw new RuntimeException("Aucune fiche ne porte l'identifiant [".$ficheId."]");

        $this->getFicheMetierService()->historiser($fiche);
        $this->redirect()->toRoute('fiche-metier',[], [], true);
    }

    public function restaurerAction()
    {
        $ficheId = $this->params()->fromRoute('id');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);

        if ($fiche === null) throw new RuntimeException("Aucune fiche ne porte l'identifiant [".$ficheId."]");

        $this->getFicheMetierService()->restaurer($fiche);
        $this->redirect()->toRoute('fiche-metier',[], [], true);
    }
}