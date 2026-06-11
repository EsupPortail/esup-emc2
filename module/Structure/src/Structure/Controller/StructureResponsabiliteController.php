<?php

namespace Structure\Controller;

use Application\Provider\Parametre\GlobalParametres;
use DateTime;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use RuntimeException;
use Structure\Entity\Db\StructureGestionnaire;
use Structure\Entity\Db\StructureResponsable;
use Structure\Form\Responsabilite\ResponsabiliteFormAwareTrait;
use Structure\Provider\Role\RoleProvider;
use Structure\Service\Structure\StructureServiceAwareTrait;
use Structure\Service\StructureGestionnaire\StructureGestionnaireServiceAwareTrait;
use Structure\Service\StructureResponsable\StructureResponsableServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;

/**
 * @desc Ce controller servira à faire la gestion des déclatations des responsabilités (responsable / gestionnaire / ...)
 * de structures. On va réutiliser le fonctionnement des chaînes hiérarchiques pour avoir à la fois des responsabilités
 * synchronisées et des responsabilités saisies
 */

class StructureResponsabiliteController extends AbstractActionController
{
    use ParametreServiceAwareTrait;
    use StructureServiceAwareTrait;
    use StructureGestionnaireServiceAwareTrait;
    use StructureResponsableServiceAwareTrait;
    use RoleServiceAwareTrait;
    use ResponsabiliteFormAwareTrait;

    public function afficherResponsabiliteAction(): ViewModel
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $roleId = $this->params()->fromRoute('role');
        $role = $this->getRoleService()->getRepo()->findOneBy(['roleId' => $roleId]);

        $responsabilites = match ($role?->getRoleId()) {
            RoleProvider::GESTIONNAIRE => $this->getStructureGestionnaireService()->getStructureGestionnaireByStructure($structure, true),
            RoleProvider::RESPONSABLE => $this->getStructureResponsableService()->getStructureResponsableByStructure($structure, true),
            default => throw new RuntimeException("StructureResponsabiliteController::afficherResponsabiliteAction() : le role [" . $role?->getRoleId() . "] est non prévu"),
        };

        $now = new DateTime();

        $revoquees = [];
        $historisees = [];
        $valides = [];
        foreach ($responsabilites as $responsabilite) {
            if ($responsabilite->getSourceId() !== 'EMC2' and $responsabilite->estHistorise()) {
                $revoquees[] = $responsabilite;
            } else {
                if ($responsabilite->estHistorise()) {
                    $historisees[] = $responsabilite;
                } else {
                    $valides[] = $responsabilite;
                }
            }
        }

        $terminees = [];
        $encours = [];
        $avenir = [];
        foreach ($valides as $valide) {
            if ($valide->estFini($now)) $terminees[] = $valide;
            if (!$valide->estCommence()) $avenir[] = $valide;
            if ($valide->estEnCours()) $encours[] = $valide;
        }

        return new ViewModel([
            'title' => "Affichage des [<span style='color:white;font-weight: bold'>".$role->getLibelle()."</span>] pour [" . $structure->getLibelleLong() ."]",
            'structure' => $structure,
            'role' => $role,
            'responsabilites' => $responsabilites,

            'appName' => $this->getParametreService()->getValeurForParametre(GlobalParametres::TYPE, GlobalParametres::APP_NAME),
            'revoquees' => $revoquees,
            'historisees' => $historisees,
            'terminees' => $terminees,
            'encours' => $encours,
            'avenir' => $avenir,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $roleId = $this->params()->fromRoute('role');
        $role = $this->getRoleService()->getRepo()->findOneBy(['roleId' => $roleId]);

        $responsabilite = match ($role?->getRoleId()) {
            RoleProvider::GESTIONNAIRE => new StructureGestionnaire(),
            RoleProvider::RESPONSABLE => new StructureResponsable(),
            default => throw new RuntimeException("StructureResponsabiliteController::ajouterAction() : le role [" . $role?->getRoleId() . "] est non prévu"),
        };
        $responsabilite->setStructure($structure);

        $form = $this->getResponsabiliteForm();
        $form->setAttribute('action', $this->url()->fromRoute('structure/responsabilite/ajouter', ['structure' => $structure?->getId(), 'role' => $role?->getRoleId()], [], true));
        $form->bind($responsabilite);
        $form->get('responsable')->setLabel($role->getLibelle() . " <span class='icon icon-obligatoire' title='Champ obligatoire'></span>");

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                if ($data['historisation'] === '1') {
                    switch ($role?->getRoleId()) {
                        case RoleProvider::GESTIONNAIRE :
                            $this->getStructureGestionnaireService()->historiseAll($responsabilite->getAgent());
                            break;
                        case RoleProvider::RESPONSABLE :
                            $this->getStructureResponsableService()->historiseAll($responsabilite->getAgent());
                            break;
                    }
                }
                if ($data['cloture'] === '1') {
                    switch ($role?->getRoleId()) {
                        case RoleProvider::GESTIONNAIRE :
                            $this->getStructureGestionnaireService()->clotureAll($responsabilite->getAgent());
                            break;
                        case RoleProvider::RESPONSABLE :
                            $this->getStructureResponsableService()->clotureAll($responsabilite->getAgent());
                            break;
                    }
                }
                $id = $responsabilite->generateId();
                $responsabilite->setId($id);
                $responsabilite->setSourceId("EMC2");
                $responsabilite->setInsertedOn(new DateTime());
                switch ($role?->getRoleId()) {
                    case RoleProvider::GESTIONNAIRE :
                        $this->getStructureGestionnaireService()->create($responsabilite);
                        break;
                    case RoleProvider::RESPONSABLE :
                        $this->getStructureResponsableService()->create($responsabilite);
                        break;
                }
                exit();
            }
        }

        $titre = match ($role?->getRoleId()) {
            RoleProvider::GESTIONNAIRE => "Ajout d'un·e gestionnaire",
            RoleProvider::RESPONSABLE => "Ajout d'un·e responsable",
            default => throw new RuntimeException("StructureResponsabiliteController::ajouterAction() : le role [" . $role?->getRoleId() . "] est non prévu"),
        };

        $vm = new ViewModel([
            'title' => $titre,
            'form' => $form,
            'js' => ($structure) ? "$('#structure-autocomplete').parent().hide();" : "",
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $responsabiliteId = $this->params()->fromRoute('responsabilite');
        $roleId = $this->params()->fromRoute('role');
        $role = $this->getRoleService()->getRepo()->findOneBy(['roleId' => $roleId]);

        $responsabilite = match ($role?->getRoleId()) {
            RoleProvider::GESTIONNAIRE => $this->getStructureGestionnaireService()->getStructureGestionnaire($responsabiliteId),
            RoleProvider::RESPONSABLE => $this->getStructureResponsableService()->getStructureResponsable($responsabiliteId),
            default => throw new RuntimeException("StructureResponsabiliteController::modifierAction() : le role [" . $role?->getRoleId() . "] est non prévu"),
        };

        $form = $this->getResponsabiliteForm();
        $form->setAttribute('action', $this->url()->fromRoute('structure/responsabilite/modifier', ['responsabilite' => $responsabilite?->getId(), 'role' => $role?->getRoleId()], [], true));
        $form->bind($responsabilite);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $id = $responsabilite->generateId();
                $responsabilite->setId($id);
                $responsabilite->setUpdatedOn(new DateTime());
                switch ($role->getRoleId()) {
                    case RoleProvider::GESTIONNAIRE:
                        $this->getStructureGestionnaireService()->update($responsabilite);
                        break;
                    case RoleProvider::RESPONSABLE:
                        $this->getStructureResponsableService()->update($responsabilite);
                        break;
                }
                exit();
            }
        }

        $titre = match ($role->getRoleId()) {
            RoleProvider::GESTIONNAIRE => "Modification d'un·e gestionnaire",
            RoleProvider::RESPONSABLE => "Modification d'un·e responsable",
            default => throw new RuntimeException("StructureResponsabiliteController::modifierAction() : Le role [" . $role->getRoleId() . "] est non géré"),
        };

        $vm = new ViewModel([
            'title' => $titre,
            'form' => $form,
            'js' => "$('#cloture').parent().hide(); $('#historisation').parent().hide();" . (($responsabilite->getStructure()) ? "$('#structure-autocomplete').parent().hide();" : ""),
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $responsabiliteId = $this->params()->fromRoute('responsabilite');
        $roleId = $this->params()->fromRoute('role');
        $role = $this->getRoleService()->getRepo()->findOneBy(['roleId' => $roleId]);

        switch ($role?->getRoleId()) {
            case RoleProvider::GESTIONNAIRE :
                $responsabilite = $this->getStructureGestionnaireService()->getStructureGestionnaire($responsabiliteId);
                $this->getStructureGestionnaireService()->historise($responsabilite);
                break;
            case RoleProvider::RESPONSABLE :
                $responsabilite = $this->getStructureResponsableService()->getStructureResponsable($responsabiliteId);
                $this->getStructureResponsableService()->historise($responsabilite);
                break;
            default :
                throw new RuntimeException("StructureResponsabiliteController::historiserAction() : le role [" . $role?->getRoleId() . "] est non prévu");
        }

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        /** @see \Structure\Controller\StructureController::descriptionAction() */
        return $this->redirect()->toRoute('structure/description', ['structure' => $responsabilite->getStructure()->getId()], [], true);
    }

    public function restaurerAction(): Response
    {
        $responsabiliteId = $this->params()->fromRoute('responsabilite');
        $roleId = $this->params()->fromRoute('role');
        $role = $this->getRoleService()->getRepo()->findOneBy(['roleId' => $roleId]);

        switch ($role?->getRoleId()) {
            case RoleProvider::GESTIONNAIRE :
                $responsabilite = $this->getStructureGestionnaireService()->getStructureGestionnaire($responsabiliteId);
                $this->getStructureGestionnaireService()->restore($responsabilite);
                break;
            case RoleProvider::RESPONSABLE :
                $responsabilite = $this->getStructureResponsableService()->getStructureResponsable($responsabiliteId);
                $this->getStructureResponsableService()->restore($responsabilite);
                break;
            default :
                throw new RuntimeException("StructureResponsabiliteController::restaurerAction() : le role [" . $role?->getRoleId() . "] est non prévu");
        }

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        /** @see \Structure\Controller\StructureController::descriptionAction() */
        return $this->redirect()->toRoute('structure/description', ['structure' => $responsabilite->getStructure()->getId()], [], true);
    }

    public function supprimerAction(): ViewModel
    {
        $responsabiliteId = $this->params()->fromRoute('responsabilite');
        $roleId = $this->params()->fromRoute('role');
        $role = $this->getRoleService()->getRepo()->findOneBy(['roleId' => $roleId]);

        $responsabilite = match ($role?->getRoleId()) {
            RoleProvider::GESTIONNAIRE => $this->getStructureGestionnaireService()->getStructureGestionnaire($responsabiliteId),
            RoleProvider::RESPONSABLE => $this->getStructureResponsableService()->getStructureResponsable($responsabiliteId),
            default => throw new RuntimeException("StructureResponsabiliteController::modifierAction() : le role [" . $role?->getRoleId() . "] est non prévu"),
        };


        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                switch ($role?->getRoleId()) {
                    case RoleProvider::GESTIONNAIRE :
                        $this->getStructureGestionnaireService()->delete($responsabilite);
                        break;
                    case RoleProvider::RESPONSABLE  :
                        $this->getStructureResponsableService()->delete($responsabilite);
                        break;
                }
            }
            exit();
        }

        $vm = new ViewModel();
        if ($responsabilite !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression d'une responsabilité pour  " . $responsabilite->getStructure()->getLibelleLong(),
                'text' => "La suppression est définitive, êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('structure/responsabilite/supprimer', ["responsabilite" => $responsabilite->getId(), 'role' => $role->getRoleId()], [], true),
            ]);
        }
        return $vm;
    }
}
