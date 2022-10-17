<?php

namespace Formation\Controller;

use Formation\Provider\Parametre\FormationParametres;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenApp\Exception\RuntimeException;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class ProjetPersonnelController extends  AbstractActionController {
    use ParametreServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $param = $this->getParametreService()->getParametreByCode(FormationParametres::TYPE, FormationParametres::URL_PPP);
        if ($param === null) throw new RuntimeException("Parametre non défini [".FormationParametres::TYPE.",".FormationParametres::URL_PPP."]");
        return new ViewModel([
            'lien' => $param->getValeur(),
        ]);
    }
}