<?php

namespace FicheReferentiel\Controller;

use FicheReferentiel\Service\FicheReferentiel\FicheReferentielServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class FicheReferentielController extends AbstractActionController
{
    use FicheReferentielServiceAwareTrait;

    public function indexAction(): ViewModel
    {
        $fiches = $this->getFicheReferentielService()->getFichesReferentiels(true);

        return new ViewModel([
            'fiches' => $fiches,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $fiche = $this->getFicheReferentielService()->getRequestedFicheReferentiel($this);

        return new ViewModel([
            'ficheReferentiel' => $fiche,
        ]);
    }
}