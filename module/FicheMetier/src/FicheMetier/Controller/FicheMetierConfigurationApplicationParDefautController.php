<?php

namespace FicheMetier\Controller;

use Element\Form\SelectionApplication\SelectionApplicationFormAwareTrait;
use Element\Service\Application\ApplicationServiceAwareTrait;
use FicheMetier\Entity\Db\FicheMetierConfigurationApplicationParDefaut;
use FicheMetier\Provider\Parametre\FicheMetierParametres;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\FicheMetierConfigurationApplicationParDefaut\FicheMetierConfigurationApplicationParDefautServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;




/**
 * On ne proposera pas de modification, car cela ne fait pas de sens pour le moment (la seule information saisie est
 * l'application).
 * Ainsi, on pourra aussi faire l'ajout de plusieurs applications
 */

class FicheMetierConfigurationApplicationParDefautController extends AbstractActionController
{

    /** @method FlashMessenger flashMessenger() */

    use ApplicationServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use FicheMetierConfigurationApplicationParDefautServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use SelectionApplicationFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $applications = $this->getFicheMetierConfigurationApplicationParDefautService()->getFicheMetierConfigurationApplicationsParDefaut(true);
        $parametres        =  $this->getParametreService()->getParametresByCategorieCode(FicheMetierParametres::TYPE);

        $vm = new ViewModel([
            'applications' => $applications,
            'parametres'   => $parametres,
        ]);
        $vm->setTemplate('fiche-metier/configuration/applications-par-defaut');
        return $vm;
    }

    public function ajouterAction(): ViewModel
    {
        $form = $this->getSelectionApplicationForm();
        $form->setAttribute('action',$this->url()->fromRoute('fiche-metier/configuration/applications-par-defaut/ajouter', [], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            if (isset($data['applications'])) {
                foreach ($data['applications'] as $applicationId) {
                    $application = $this->getApplicationService()->getApplication($applicationId);
                    if (!$this->getFicheMetierConfigurationApplicationParDefautService()->hasApplication($application))
                    {
                        $defaut = new FicheMetierConfigurationApplicationParDefaut();
                        $defaut->setApplication($application);
                        $this->getFicheMetierConfigurationApplicationParDefautService()->create($defaut);
                    }
                }
            }
            exit();
        }

        $vm = new ViewModel([
            'title' => "Ajouter des applications par défaut",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function supprimerAction(): ViewModel
    {
        $applicationParDefaut = $this->getFicheMetierConfigurationApplicationParDefautService()->getRequestedFicheMetierConfigurationApplicationParDefaut($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFicheMetierConfigurationApplicationParDefautService()->delete($applicationParDefaut);
            exit();
        }

        $vm = new ViewModel();
        if ($applicationParDefaut !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'application par défaut" . $applicationParDefaut->getApplication()->getLibelle(),
                'text' => "La suppression est définitive, êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('fiche-metier/configuration/applications-par-defaut/supprimer', ["application-par-defaut" => $applicationParDefaut->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function reappliquerAction(): ViewModel
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $fiches = $this->getFicheMetierService()->getFichesMetiers();
                foreach ($fiches as $fiche) {
                    $this->getFicheMetierConfigurationApplicationParDefautService()->applyDefault($fiche);
                }
                $this->flashMessenger()->addSuccessMessage("Ré-application terminée");
                exit();
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/confirmation');
        $vm->setVariables([
            'title' => "Réapplication des applications par défaut sur les fiches métiers",
            'text' => "La réapplication modifiera toutes les fiches métiers. Êtes-vous sûr&middot;e de vouloir continuer ?",
            'action' => $this->url()->fromRoute('fiche-metier/configuration/applications-par-defaut/reappliquer', [], [], true),
        ]);
        return $vm;

    }

}