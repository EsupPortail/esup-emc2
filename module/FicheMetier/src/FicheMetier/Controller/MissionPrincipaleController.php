<?php

namespace FicheMetier\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleFormAwareTrait;
use Carriere\Entity\Db\NiveauEnveloppe;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeFormAwareTrait;
use Carriere\Service\NiveauEnveloppe\NiveauEnveloppeServiceAwareTrait;
use Element\Form\SelectionApplication\SelectionApplicationFormAwareTrait;
use Element\Form\SelectionCompetence\SelectionCompetenceFormAwareTrait;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Entity\Db\MissionActivite;
use FicheMetier\Service\MissionActivite\MissionActiviteServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Metier\Form\SelectionnerDomaines\SelectionnerDomainesFormAwareTrait;

class MissionPrincipaleController extends AbstractActionController
{
    use MissionActiviteServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use NiveauEnveloppeServiceAwareTrait;

    use ModifierLibelleFormAwareTrait;
    use NiveauEnveloppeFormAwareTrait;
    use SelectionnerDomainesFormAwareTrait;
    use SelectionApplicationFormAwareTrait;
    use SelectionCompetenceFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $missions = $this->getMissionPrincipaleService()->getMissionsPrincipales();

        return new ViewModel([
            'missions' => $missions,
        ]);
    }

    /** CRUD ****************************************************************************************/

    public function afficherAction() : ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);

        $vm =  new ViewModel([
            'title' => "Affichage de la mission principale",
            'modification' => false,
            'mission' => $mission,
            'fichesmetiers' => $mission->getListeFicheMetier(),
            'fichespostes' =>  $mission->getListeFichePoste(),
        ]);
        $vm->setTemplate('fiche-metier/mission-principale/modifier');
        return $vm;
    }

    public function ajouterAction() : Response
    {
        $mission = new Mission();
        $this->getMissionPrincipaleService()->create($mission);

        return $this->redirect()->toRoute('mission-principale/modifier', ['mission-principale' => $mission->getId()], [], true);
    }

    public function modifierAction() : ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);

        return new ViewModel([
            'mission' => $mission,
            'modification' => true,
            'fichesmetiers' => $mission->getListeFicheMetier(),
            'fichespostes' =>  $mission->getListeFichePoste(),
        ]);
    }

    public function historiserAction() : Response
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);
        $this->getMissionPrincipaleService()->historise($mission);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('mission-principale');
    }

    public function restaurerAction() : Response
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);
        $this->getMissionPrincipaleService()->restore($mission);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('mission-principale');
    }

    public function supprimerAction() : ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getMissionPrincipaleService()->delete($mission);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($mission !== null) {
            $nbFicheMetier = count($mission->getListeFicheMetier());
            $nbFichePoste = 0;
            $warning = "<span class='icon icon-attention'></span> Attention cette mission principale est encore associée à ".$nbFicheMetier." fiches métiers et à ".$nbFichePoste." fiches de poste.";
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la mission [".$mission->getLibelle()."]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'warning' => (($nbFicheMetier + $nbFichePoste) > 0)?$warning:null,
                'action' => $this->url()->fromRoute('mission-principale/supprimer', ["mission-principale" => $mission->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** MODIFICATION DES ELEMENTS **************************************************************************/

    public function modifierLibelleAction() : ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-principale/modifier-libelle', ['mission-principale' => $mission->getId()], [], true));
        $form->bind($mission);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data  =$request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionPrincipaleService()->update($mission);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification du libellé de la mission principale",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function gererDomainesAction() : ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);

        $form = $this->getSelectionnerDomainesForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-principale/gerer-domaines', ['mission-principale' => $mission->getId()], [], true));
        $form->bind($mission);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data  =$request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionPrincipaleService()->update($mission);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Gérer les domaines",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function gererNiveauAction() : ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);

        $niveaux = $mission->getNiveau();
        if ($niveaux === null) {
            $niveaux = new NiveauEnveloppe();
        }

        $form = $this->getNiveauEnveloppeForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-principale/gerer-niveau', ['mission-principale' => $mission->getId()], [], true));
        $form->bind($niveaux);

        $request = $this->getRequest();
        if($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                if ($niveaux->getHistoCreation()) {
                    $this->getNiveauEnveloppeService()->update($niveaux);
                } else {
                    $this->getNiveauEnveloppeService()->create($niveaux);
                    $mission->setNiveau($niveaux);
                    $this->getMissionPrincipaleService()->update($mission);
                }
            }
        }

        $vm = new ViewModel([
            'title' => "Modifier le niveau associé à la mission",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function ajouterActiviteAction() : ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);

        $activite = new MissionActivite();
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-principale/ajouter-activite', ['mission-principale' => $mission->getId()], [], true));
        $form->bind($activite);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data  =$request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionPrincipaleService()->ajouterActivite($mission, $activite);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajouter une activité à la mission principale",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierActiviteAction() : ViewModel
    {
        $activite = $this->getMissionActiviteService()->getRequestedActivite($this);
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-principale/modifier-activite', ['mission-principale' => $activite->getId()], [], true));
        $form->bind($activite);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data  =$request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionActiviteService()->update($activite);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modifier le libellé de l'activité",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function supprimerActiviteAction() : Response
    {
        $activite = $this->getMissionActiviteService()->getRequestedActivite($this);
        $mission = $activite->getMission();
        $this->getMissionActiviteService()->delete($activite);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('mission-principale/modifier', ['mission-principale' => $mission->getId()], [], true);
    }

    public function gererApplicationsAction() : ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);
        $form = $this->getSelectionApplicationForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-principale/gerer-applications', ['mission-principale' => $mission->getId()], [], true));
        $form->bind($mission);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionPrincipaleService()->update($mission);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Gestion des applications associées à la mission",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function gererCompetencesAction() : ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);
        $form = $this->getSelectionCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-principale/gerer-competences', ['mission-principale' => $mission->getId()], [], true));
        $form->bind($mission);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionPrincipaleService()->update($mission);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Gestion des compétences associées à la mission",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    /** FONCTIONS DE RECHERCHE ****************************************************************************************/

    public function rechercherAction() : JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $missions = $this->getMissionPrincipaleService()->findMissionsPrincipalesByExtendedTerm($term);
            $result = $this->getMissionPrincipaleService()->formatToJSON($missions);
            return new JsonModel($result);
        }
        exit;
    }

    /** LEFTOVERS ACTIVITE_CONTROLLER **********************/

//    public function initialiserNiveauxAction() : Response
//    {
//        $activites = $this->getActiviteService()->getActivites();
//        foreach ($activites as $activite) {
//            if ($activite->getNiveaux() === null) {
//                $inferieure = null;
//                $superieure = null;
//                /** @var FicheMetierActivite $ficheMetier */
//                foreach ($activite->getFichesMetiers() as $ficheMetier) {
//                    $niveaux = $ficheMetier->getFiche()->getMetier()->getNiveaux();
//                    if ($niveaux) {
//                        if ($inferieure === null OR $niveaux->getBorneInferieure() < $inferieure) $inferieure = $niveaux->getBorneInferieure();
//                        if ($superieure === null OR $niveaux->getBorneSuperieure() > $superieure) $superieure = $niveaux->getBorneSuperieure();
//                    }
//                }
//                if ($inferieure !== null AND $superieure !== null) {
//                    $niveaux = new NiveauEnveloppe();
//                    $niveaux->setBorneInferieure($inferieure);
//                    $niveaux->setBorneSuperieure($superieure);
//                    $niveaux->setDescription("Recupérer de l'ancien système de niveau");
//                    $this->getNiveauEnveloppeService()->create($niveaux);
//                    $activite->setNiveaux($niveaux);
//                    $this->getActiviteService()->update($activite);
//                }
//            }
//        }
//
//        return $this->redirect()->toRoute('activite');
//    }
}