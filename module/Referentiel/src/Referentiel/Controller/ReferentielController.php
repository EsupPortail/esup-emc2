<?php

namespace Referentiel\Controller;

use Element\Controller\CompetenceController;
use Element\Provider\Privilege\CompetencePrivileges;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use FicheMetier\Controller\ActiviteController;
use FicheMetier\Controller\FicheMetierController;
use FicheMetier\Controller\MissionPrincipaleController;
use FicheMetier\Provider\Privilege\ActivitePrivileges;
use FicheMetier\Provider\Privilege\FicheMetierPrivileges;
use FicheMetier\Provider\Privilege\MissionPrincipalePrivileges;
use FicheMetier\Service\Activite\ActiviteServiceAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Referentiel\Entity\Db\Referentiel;
use Referentiel\Form\Referentiel\ReferentielFormAwareTrait;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class ReferentielController extends AbstractActionController
{
    use ActiviteServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use PrivilegeServiceAwareTrait;
    use ReferentielServiceAwareTrait;
    use UserServiceAwareTrait;
    use ReferentielFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $referentiels = $this->getReferentielService()->getReferentiels(true);

        return new ViewModel([
            'referentiels' => $referentiels
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $referentiel = $this->getReferentielService()->getRequestedReferentiel($this);

        return new ViewModel([
           'title' => "Affichage du referentiel [".$referentiel?->getLibelleCourt()."]",
           'referentiel' => $referentiel
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $referentiel = new Referentiel();
        $form = $this->getReferentielForm();
        $form->setAttribute('action', $this->url()->fromRoute('referentiel/ajouter', [], [], true));
        $form->bind($referentiel);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getReferentielService()->create($referentiel);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un référentiel",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $referentiel = $this->getReferentielService()->getRequestedReferentiel($this);
        $form = $this->getReferentielForm();
        $form->setAttribute('action', $this->url()->fromRoute('referentiel/modifier', ['referentiel' => $referentiel?->getId()], [], true));
        $form->bind($referentiel);
        $form->setOldLibelleCourt($referentiel->getLibelleCourt());

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getReferentielService()->update($referentiel);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification du référentiel [".$referentiel?->getLibelleCourt()."]",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $referentiel = $this->getReferentielService()->getRequestedReferentiel($this);
        $this->getReferentielService()->historise($referentiel);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        /** @see ReferentielController::indexAction() */
        return $this->redirect()->toRoute('referentiel', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $referentiel = $this->getReferentielService()->getRequestedReferentiel($this);
        $this->getReferentielService()->restore($referentiel);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        /** @see ReferentielController::indexAction() */
        return $this->redirect()->toRoute('referentiel', [], [], true);
    }

    public function supprimerAction(): ViewModel
    {
        $referentiel = $this->getReferentielService()->getRequestedReferentiel($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                foreach ($referentiel->getActivites() as $activite) $this->getActiviteService()->delete($activite);
                foreach ($referentiel->getMissions() as $mission) $this->getMissionPrincipaleService()->delete($mission);
                foreach ($referentiel->getCompetences() as $competence) $this->getCompetenceService()->delete($competence);
                foreach ($referentiel->getFichesMetiers() as $fiche) $this->getFicheMetierService()->delete($fiche);
                $this->getReferentielService()->delete($referentiel);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($referentiel !== null) {
            $role = $this->getUserService()->getConnectedRole();
            $warnings = "";

            $nbActivite = count($referentiel->getActivites());
            if ($nbActivite > 0) {
                $warnings .= "<li>".$nbActivite . " activités ";
                if ($this->getPrivilegeService()->checkPrivilege(ActivitePrivileges::ACTIVITE_INDEX, $role)) {
                    /** @see ActiviteController::indexAction() **/
                    $urlActivite = $this->url()->fromRoute('activite', [], ['query' => ['referentiel' => $referentiel->getId()]], true);
                    $warnings .= "<a href='".$urlActivite."' class='action secondary' target='_blank'> <span class='icon icon-voir'  style='color: gray;'></span> Accéder aux activités </a>";
                }
                $warnings .= "</li>";
            }
            $nbMission = count($referentiel->getMissions());
            if ($nbMission > 0) {
                $warnings .= "<li>".$nbMission . " missions ";
                if ($this->getPrivilegeService()->checkPrivilege(MissionPrincipalePrivileges::MISSIONPRINCIPALE_INDEX, $role)) {
                    /** @see MissionPrincipaleController::indexAction() */
                    $urlMission = $this->url()->fromRoute('mission-principale', [], ['query' => ['referentiel' => $referentiel->getId()]], true);
                    $warnings .= "<a href='".$urlMission."' class='action secondary' target='_blank'> <span class='icon icon-voir'  style='color: gray;'></span> Accéder aux missions </a>";
                }
                $warnings .= "</li>";
            }

            $nbCompetence = count($referentiel->getCompetences());
            if ($nbCompetence > 0) {
                $warnings .= "<li>".$nbCompetence . " compétences ";
                if ($this->getPrivilegeService()->checkPrivilege(CompetencePrivileges::COMPETENCE_INDEX, $role)) {
                    /** @see CompetenceController::indexAction() */
                    $urlCompetence = $this->url()->fromRoute('element/competence/listing', [], ['query' => ['referentiel' => $referentiel->getId()]], true);
                    $warnings .= "<a href='".$urlCompetence."' class='action secondary' target='_blank'> <span class='icon icon-voir'  style='color: gray;'></span> Accéder aux compétences </a>";
                }
                $warnings .= "</li>";
            }

            $nbFiche = count($referentiel->getFichesMetiers());
            if ($nbFiche > 0) {
                $warnings .= "<li>".$nbFiche . " fiches métiers ";
                if ($this->getPrivilegeService()->checkPrivilege(FicheMetierPrivileges::FICHEMETIER_INDEX, $role)) {
                    /** @see FicheMetierController::indexAction() */
                    $urlCompetence = $this->url()->fromRoute('fiche-metier', [], ['query' => ['referentiel' => $referentiel->getId()]], true);
                    $warnings .= "<a href='".$urlCompetence."' class='action secondary' target='_blank'> <span class='icon icon-voir' style='color: gray;'></span> Accéder aux fiches métiers </a>";
                }
                $warnings .= "</li>";
            }
            if ($warnings !== '') {
                $texteWarning  = "<strong class='text-danger'>ATTENTION :</strong> la suppression du référentiel entraîne la suppression des ressources attachées.<br>";
                $texteWarning .= "Ressources attachées au référentiel :<ul>".$warnings."</ul>";
                $texteWarning .= "Nous vous conseillons de rattacher les ressources à un autre référentiel avant de le supprimer.";
            }


            else $texteWarning = null;

            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du référentiel [".$referentiel->getLibelleCourt()."]",
                'warning' => $texteWarning,
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('referentiel/supprimer', ["referentiel" => $referentiel->getId()], [], true),
            ]);
        }
        return $vm;
    }
}
