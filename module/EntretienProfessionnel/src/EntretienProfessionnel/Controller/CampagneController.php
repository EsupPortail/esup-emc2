<?php

namespace EntretienProfessionnel\Controller;

use Application\Entity\Db\Agent;
use Application\Service\Agent\AgentServiceAwareTrait;
use DateInterval;
use DateTime;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Form\Campagne\CampagneFormAwareTrait;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementServiceAwareTrait;
use EntretienProfessionnel\Service\Notification\NotificationServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */

class CampagneController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use RappelCampagneAvancementServiceAwareTrait;
    use StructureServiceAwareTrait;
    use CampagneFormAwareTrait;
    use UserServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $campagnes = $this->getCampagneService()->getCampagnes();

        return new ViewModel([
            'campagnes' => $campagnes,
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);

        return new ViewModel([
            'campagne' => $campagne,
            'entretiens' => $this->getCampagneService()->getEntretiensProfessionnels($campagne),
            'agents' => $this->getCampagneService()->getAgentsEligibles($campagne),
            'entretiensResponsable' => $this->getCampagneService()->getEntretiensEnAttenteResponsable($campagne),
            'entretiensAutorite' => $this->getCampagneService()->getEntretiensEnAttenteAutorite($campagne),
            'entretiensAgent' => $this->getCampagneService()->getEntretiensEnAttenteAgent($campagne),
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $campagne = new Campagne();
        $campagne->setAnnee(CampagneService::getAnneeScolaire());

        $form = $this->getCampagneForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/ajouter', [], [], true));
        $form->bind($campagne);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCampagneService()->create($campagne);
                $this->flashMessenger()->addSuccessMessage("La campagne d'entretien professionnel [".$campagne->getAnnee()."] vient d'être créée.");

               $this->getRappelCampagneAvancementService()->creer($campagne);
               $this->getNotificationService()->triggerCampagneOuverturePersonnels($campagne);
               $this->getNotificationService()->triggerCampagneOuvertureDirections($campagne);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une campagne d'entretien professionnel",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);

        $form = $this->getCampagneForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/modifier', ['campagne' => $campagne->getId()], [], true));
        $form->bind($campagne);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCampagneService()->update($campagne);
                $this->flashMessenger()->addSuccessMessage("La campagne d'entretien professionnel [".$campagne->getAnnee()."] vient d'être modifiée.");
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une campagne d'entretien professionnel",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction() : Response
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $this->getCampagneService()->historise($campagne);
        return $this->redirect()->toRoute('entretien-professionnel', [], ['fragment' => 'campagne'], true);
    }

    public function restaurerAction() : Response
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $this->getCampagneService()->restore($campagne);
        return $this->redirect()->toRoute('entretien-professionnel', [], ['fragment' => 'campagne'], true);
    }

    public function detruireAction() : ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCampagneService()->delete($campagne);
            exit();
        }

        $vm = new ViewModel();
        if ($campagne !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la campagne " . $campagne->getAnnee(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/campagne/detruire', ["campagne" => $campagne->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /**
     * Action affichant une campagne d'entretien professionnel pour une structure
     * <!> les agents doivent être complétement hydrater sinon les calculs d'affectations, de grades et d'obligation seront erronés
     */
    public function structureAction() : ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $role = $this->getUserService()->getConnectedRole();
        $utilisateur = $this->getUserService()->getConnectedUser();
        $selecteur = $this->getStructureService()->getStructuresByCurrentRole($utilisateur, $role);

        $structures = $this->getStructureService()->getStructuresFilles($structure, true);
        $agentsAll = $this->getAgentService()->getAgentsByStructuresAndDate($structures, $campagne->getDateDebut());

        /** Filtrage des agents (seul les agents ayants le statut adminstratif lors de la campagne sont éligibles) */
        $agents = [];
        /** @var Agent $agent */
        foreach ($agentsAll as $agent) {
            $isAdministratif = false;
            $statuts = $agent->getStatutsActifs($campagne->getDateDebut());
            foreach ($statuts as $statut) {
                if ($statut->isAdministratif()) {
                    $isAdministratif = true; break;
                }
            }
            if ($isAdministratif) $agents[] = $agent;
        }
        usort($agents, function (Agent $a, Agent $b) {
            $aaa = $a->getNomUsuel()." ".$a->getPrenom();
            $bbb = $b->getNomUsuel()." ".$b->getPrenom();
            return $aaa > $bbb;
        });

        /** Les agents avec obligation d'entretien sont ceux qui était en poste 12 mois avant la fin de campagne */
        $obligatoires = [];
        $facultatifs = [];
        $dateMinEnPoste = (DateTime::createFromFormat('d/m/Y', $campagne->getDateFin()->format('d/m/Y')))->sub(new DateInterval('P12M'));
        foreach ($agents as $agent) {
            if (!empty($agent->getAffectationsActifs($dateMinEnPoste))) $obligatoires[] = $agent; else $facultatifs[] = $agent;
        }

        $entretiens = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($campagne, $agents);
        $finalises = [];
        $encours = [];
        foreach ($entretiens as $entretien) {
            if ($entretien->getEtat()->getCode() === EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT) {
                $finalises[] = $entretien;
            } else {
                $encours[] = $entretien;
            }
        }

        $last =  $this->getCampagneService()->getLastCampagne();
        $campagnes =  $this->getCampagneService()->getCampagnesActives();
        $campagnes[] = $last;
        usort($campagnes, function (Campagne $a, Campagne $b) { return $a->getDateDebut() > $b->getDateDebut();});

        return new ViewModel([
            'campagne' => $campagne,
            'campagnes' => $campagnes,
            'structure' => $structure,
            'selecteur' => $selecteur,
            'structures' => $structures,
            'agents' => $agents,

            'entretiens' => $entretiens,
            'encours' => $encours,
            'finalises' => $finalises,

            'dateMinEnPoste' => $dateMinEnPoste,
            'obligatoires' => $obligatoires,
            'facultatifs' => $facultatifs,
        ]);
    }
}