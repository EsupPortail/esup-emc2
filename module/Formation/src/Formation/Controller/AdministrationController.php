<?php

namespace Formation\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenParametre\Service\Categorie\CategorieServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenRenderer\Service\Template\TemplateServiceAwareTrait;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;

class AdministrationController extends AbstractActionController
{
    use CategorieServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use PrivilegeServiceAwareTrait;
    use RoleServiceAwareTrait;
    use TemplateServiceAwareTrait;

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

    public function privilegeAction() : ViewModel
    {
        $namespace = 'Formation\Provider\Privilege';

        $roles = $this->roleService->findAll();
        $privilegesByCategorie = $this->privilegeService->listByCategorie($namespace);

        $vm = new ViewModel([
            'roles' => $roles,
            'privilegesByCategorie' => $privilegesByCategorie,
            'gestion' => 'off',
        ]);
        $vm->setTemplate('unicaen-privilege/privilege/index');
        return $vm;

    }

    public function templateAction() : ViewModel
    {
        $templates = $this->getTemplateService()->getTemplatesByNamespace('Formation\Provider\Template');

        $vm =  new ViewModel([
            'templates' => $templates,
        ]);
        $vm->setTemplate('unicaen-renderer/template/index');
        return $vm;
    }
}