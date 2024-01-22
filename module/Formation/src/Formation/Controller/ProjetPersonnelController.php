<?php

namespace Formation\Controller;

use Formation\Provider\Parametre\FormationParametres;
use Formation\Provider\Template\TextTemplates;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenApp\Exception\RuntimeException;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;

class ProjetPersonnelController extends AbstractActionController
{
    use ParametreServiceAwareTrait;
    use RenduServiceAwareTrait;

    public function indexAction(): ViewModel
    {
        $param = $this->getParametreService()->getParametreByCode(FormationParametres::TYPE, FormationParametres::URL_PPP);
        if ($param === null) throw new RuntimeException("Parametre non défini [" . FormationParametres::TYPE . "," . FormationParametres::URL_PPP . "]");

        $texte = $this->getRenduService()->generateRenduByTemplateCode(TextTemplates::MES_FORMATIONS_PROJETPERSO, [] , false);

        return new ViewModel([
            'texte' => $texte->getCorps(),
            'lien' => $param->getValeur(),
        ]);
    }
}