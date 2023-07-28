<?php

namespace Application\Controller;

use Application\Provider\Parametre\GlobalParametres;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenEtat\Service\EtatCategorie\EtatCategorieServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenEvenement\Service\Type\TypeServiceAwareTrait;
use UnicaenParametre\Service\Categorie\CategorieServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenRenderer\Service\Template\TemplateServiceAwareTrait;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;

class VerificationController extends AbstractActionController {
    use PrivilegeServiceAwareTrait;
    use TemplateServiceAwareTrait;
    use EtatCategorieServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use CategorieServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RoleServiceAwareTrait;
    use TypeServiceAwareTrait;
    use ValidationTypeServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $modules = ['Application', 'Carriere', 'Element', 'EntretienProfessionnel', 'Formation', 'Metier', 'Structure'];

        return new ViewModel([
            'modules' => $modules,
            'installation_path' => $this->getParametreService()->getParametreByCode(GlobalParametres::TYPE, GlobalParametres::INSTALL_PATH)->getValeur(),
            'templateService' => $this->getTemplateService(),
            'privilegeService' => $this->getPrivilegeService(),
            'etatCategorieService' => $this->getEtatCategorieService(),
            'etatTypeService' => $this->getEtatTypeService(),
            'evenementTypeService' => $this->getTypeService(),
            'roleService' => $this->getRoleService(),
            'validationTypeService' => $this->getValidationTypeService(),
            'parametreService' => $this->getParametreService(),
            'categorieService' => $this->getCategorieService(),
        ]);
    }
}