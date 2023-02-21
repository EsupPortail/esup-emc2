<?php

namespace Formation\Controller;

use Formation\Provider\Etat\DemandeExterneEtats;
use Formation\Provider\Etat\InscriptionEtats;
use Formation\Provider\Etat\SessionEtats;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenIndicateur\Service\Indicateur\IndicateurServiceAwareTrait;
use UnicaenIndicateur\Service\TableauDeBord\TableauDeBordServiceAwareTrait;
use UnicaenParametre\Service\Categorie\CategorieServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenRenderer\Service\Template\TemplateServiceAwareTrait;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;

class AdministrationController extends AbstractActionController
{
    use CategorieServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use IndicateurServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use PrivilegeServiceAwareTrait;
    use RoleServiceAwareTrait;
    use TableauDeBordServiceAwareTrait;
    use TemplateServiceAwareTrait;

    public function indicateurAction() : ViewModel
    {
        $indicateurs = $this->getIndicateurService()->getIndicateursByNamespace('Formation\Provider\Indicateur');
        $tableaux = $this->getTableauDeBordService()->getTableauxDeBordByNamespace('Formation\Provider\Indicateur');

        $vm =  new ViewModel([
            'indicateurs' => $indicateurs,
            'tableaux' => $tableaux,
            'retour' => $this->url()->fromRoute('formation/administration/indicateur', [], ['force_canonical' => true], true),
        ]);
        return $vm;
    }


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

    public function etatAction() : ViewModel
    {
        $types = [
            $this->getEtatTypeService()->getEtatTypeByCode(SessionEtats::TYPE),
            $this->getEtatTypeService()->getEtatTypeByCode(InscriptionEtats::TYPE),
            $this->getEtatTypeService()->getEtatTypeByCode(DemandeExterneEtats::TYPE),
        ];

        $vm = new ViewModel([
            'etatTypes' => $types,
        ]);
        return $vm;
    }
}