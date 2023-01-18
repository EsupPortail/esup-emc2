<?php

namespace Formation\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenParametre\Service\Categorie\CategorieServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class AdministrationController extends AbstractActionController
{
    use CategorieServiceAwareTrait;
    use ParametreServiceAwareTrait;

    public function parametreAction() : ViewModel
    {
        $categorie = $this->getCategorieService()->getCategoriebyCode('FORMATION');
        $parametres = $this->getParametreService()->getParametresByCategorie($categorie);

        $vm =  new ViewModel([
            'categories' => [$categorie],
            'selection' => $categorie,
            'parametres' => $parametres,
        ]);
        $vm->setTemplate('unicaen-parametre/categorie/index');
        return $vm;
    }
}