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
        $namespace = 'Formation\Provider\Template';

        $type = $this->params()->fromQuery('type');
        $type = ($type !== '')?$type:null;

        $templates = $this->getTemplateService()->getTemplatesByTypeAndNamespaces($type, $namespace);
        $namespaces = [$namespace];
        $types = $this->getTemplateService()->getTypes();

        $vm =  new ViewModel([
            'title' => 'Gestion des templates',
            'templates' => $templates,
            'namespaces' => $namespaces,
            'types' => $types,
            'namespace' => $namespace,
            'type' => $type,
        ]);
        $vm->setTemplate('unicaen-renderer/template/index');
        return $vm;
    }

    public function etatAction() : ViewModel
    {
        $types = [
            SessionEtats::TYPE => $this->getEtatTypeService()->getEtatsTypesByCategorieCode(SessionEtats::TYPE),
            InscriptionEtats::TYPE => $this->getEtatTypeService()->getEtatsTypesByCategorieCode(InscriptionEtats::TYPE),
            DemandeExterneEtats::TYPE => $this->getEtatTypeService()->getEtatsTypesByCategorieCode(DemandeExterneEtats::TYPE),
        ];

        $vm = new ViewModel([
            'etatTypes' => $types,
        ]);
        return $vm;
    }
}