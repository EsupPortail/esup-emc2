<?php

namespace FicheMetier\Controller;

use FicheMetier\Provider\Parametre\FicheMetierParametres;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class FicheMetierConfigurationAffichageController extends AbstractActionController
{
    use ParametreServiceAwareTrait;

    public function indexAction(): ViewModel
    {
        $parametres = $this->getParametreService()->getParametresByCategorieCode(FicheMetierParametres::TYPE);

        $vm = new ViewModel([
            'parametres' => $parametres,
        ]);
        $vm->setTemplate('fiche-metier/configuration/affichage');
        return $vm;
    }

    public function toggleParametreAction(): JsonModel
    {
        $type = $this->params()->fromRoute('type');
        $label = $this->params()->fromRoute('parametre');

        $parametre = $this->getParametreService()->getParametreByCode($type, $label);
        if ($parametre->getValeur() === 'true') {
            $parametre->setValeur('false');
            $this->getParametreService()->update($parametre);
            return new JsonModel([
                'value' => false,
                'success' => true
            ]);
        }
        if ($parametre->getValeur() === 'false') {
            $parametre->setValeur('true');
            $this->getParametreService()->update($parametre);
            return new JsonModel([
                'value' => true,
                'success' => true
            ]);
        }
        $JM = new JsonModel(['success' => false]);
        $JM->setTerminal(true);
        return $JM;
    }
}
