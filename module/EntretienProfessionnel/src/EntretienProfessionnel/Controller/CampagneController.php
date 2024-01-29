<?php

namespace EntretienProfessionnel\Controller;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAffectation;
use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use DateInterval;
use DateTime;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Form\Campagne\CampagneFormAwareTrait;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Provider\Parametre\EntretienProfessionnelParametres;
use EntretienProfessionnel\Provider\Validation\EntretienProfessionnelValidations;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementAutoriteServiceAwareTrait;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementSuperieurServiceAwareTrait;
use EntretienProfessionnel\Service\Notification\NotificationServiceAwareTrait;
use Exception;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use RuntimeException;
use Structure\Provider\Parametre\StructureParametres;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\View\Model\CsvModel;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */

class CampagneController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RappelCampagneAvancementAutoriteServiceAwareTrait;
    use RappelCampagneAvancementSuperieurServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;
    
    use CampagneFormAwareTrait;

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
            'entretiensCompletes' => $this->getCampagneService()->getEntretiensCompletes($campagne),
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

               $this->getRappelCampagneAvancementAutoriteService()->creer($campagne);
               $this->getRappelCampagneAvancementSuperieurService()->creer($campagne);
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

    /**
     * Fonction permettant de re-expédier les notifications d'ouverture de campagne.
     * Attention : il n'y a pas de bouton pour invoquer cette fonction, il faut utiliser la route directement.
     */
    public function notifierOuvertureAction() : ViewModel {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $this->getNotificationService()->triggerCampagneOuverturePersonnels($campagne);
        $this->getNotificationService()->triggerCampagneOuvertureDirections($campagne);

        return new ViewModel(['title' => "Notification de l'ouverture de la campagne"]);
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

    /** Action de la page d'affichage d'une campagne ******************************************************************/

    public function demanderValidationSuperieurAction() : ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $enAttente =  $this->getCampagneService()->getEntretiensEnAttenteResponsable($campagne);

        $entretiens = []; $listes = [];
        foreach ($enAttente as $entretienListe) {
            /** @var EntretienProfessionnel $entretien */
            foreach ($entretienListe as $entretien) {
                if ($entretien->estNonHistorise()) {
                    $superieur = $entretien->getResponsable();
                    $listes[$superieur->getId()] = $superieur;
                    $entretiens[$superieur->getId()][] = $entretien;
                }
            }
        }

        $count = 0;
        $texte  = "Liste des notifications :";
        $texte .= "<ul>";
        foreach ($listes as $superieur) {
            $mail = $this->getNotificationService()->triggerRappelValidationSuperieur($superieur, $campagne, $entretiens[$superieur->getId()]);
            $texte .= "<li> Notification faite vers ".$superieur->getDenomination(). " (".$superieur->getEmail().") mail#".$mail->getId(). "</li>";
            $count++;
        }
        $texte .= "</ul>";
        $texte .= $count . " notification·s <br/>";

        $vm = new ViewModel([
            'title' => "Rapport de notification",
            'reponse' => $texte,
        ]);
        $vm->setTemplate('default/reponse');
        return $vm;
    }

    public function demanderValidationAutoriteAction() : ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $enAttente =  $this->getCampagneService()->getEntretiensEnAttenteAutorite($campagne);

        $entretiens = []; $problemes = []; $listes = [];
        foreach ($enAttente as $entretienListe) {
            /** @var EntretienProfessionnel $entretien */
            foreach ($entretienListe as $entretien) {
                if ($entretien->estNonHistorise()) {
                    $agent = $entretien->getAgent();
                    $autorites_tmp = array_map(function (AgentAutorite $aa) {
                        return $aa->getAutorite();
                    }, $agent->getAutorites());
                    $autorites = [];
                    foreach ($autorites_tmp as $autorite) {
                        if ($autorite !== $entretien->getResponsable()) $autorites[] = $autorite;
                    }

                    if (!empty($autorites)) {
                        foreach ($autorites as $autorite) {
                            $listes[$autorite->getId()] = $autorite;
                            $entretiens[$autorite->getId()][] = $entretien;
                        }
                    } else {
                        $problemes[] = $entretien;
                    }
                }
            }
        }

        $count = 0;
        $texte  = "Liste des notifications :";
        $texte .= "<ul>";
        foreach ($listes as $autorite) {
            $mail = $this->getNotificationService()->triggerRappelValidationAutorite($autorite, $campagne, $entretiens[$autorite->getId()]);
            $texte .= "<li> Notification faite vers ".$autorite->getDenomination(). " (".$autorite->getEmail().") mail#".$mail->getId(). "</li>";
            $count++;
        }
        $texte .= "</ul>";
        $texte .= $count . " notification·s <br/>";

        $texte .= "Liste des problèmes : ";
        $texte .= "<ul>";
        foreach ($problemes as $probleme) {
            $texte .= "<li> Entretien de ".$probleme->getAgent()->getDenomination() . "</li>";
        }
        $texte .= "</ul>";

        $vm = new ViewModel([
            'title' => "Rapport de notification",
            'reponse' => $texte,
        ]);
        $vm->setTemplate('default/reponse');
        return $vm;
    }

    public function demanderValidationAgentAction() : ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $enAttente =  $this->getCampagneService()->getEntretiensEnAttenteAgent($campagne);

        $count = 0;
        $texte  = "Liste des notifications :";
        $texte .= "<ul>";
        foreach ($enAttente as $entretienListe) {
            /** @var EntretienProfessionnel $entretien */
            foreach ($entretienListe as $entretien) {
                if ($entretien->estNonHistorise()) {
                    $agent = $entretien->getAgent();
                    $mail = $this->getNotificationService()->triggerRappelValidationAgent($entretien);
                    $texte .= "<li> Notification faite vers " . $agent->getDenomination() . " (" . $agent->getEmail() . ") mail#" . $mail->getId() . "</li>";
                    $count++;
                }
            }
        }
        $texte .= "</ul>";
        $texte .= $count . " notification·s";

        $vm = new ViewModel([
            'title' => "Rapport de notification",
            'reponse' => $texte,
        ]);
        $vm->setTemplate('default/reponse');
        return $vm;
    }

    public function extraireAction() : CsvModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);

        /** @var Agent[] $agents */
        $agents = $this->getCampagneService()->getAgentsEligibles($campagne);
        $entretiens = $campagne->getEntretiensProfessionnels();

        $headers = ['Prénom', 'Nom', 'Affectations', 'Supérieur·es', 'Autorités', 'Responsable d\'entretien', 'Validation du responsable', 'Validation de l\'autorité', 'Validation de l\'agent'];
        $resume = [];
        foreach ($agents as $agent) {
            $resume[$agent->getId()] = [
                'Prénom' => $agent->getPrenom(),
                'Nom' => $agent->getNomUsuel()??$agent->getNomFamille(),
                'Affectations' => implode("\n",array_map(function (AgentAffectation $a) { return $a->getStructure()->getLibelleLong(); },$agent->getAffectationsActifs($campagne->getDateDebut()))),
                'Supérieur·es' => implode("\n",array_map(function (AgentSuperieur $a) { return $a->getSuperieur()->getDenomination(); },$agent->getSuperieurs())),
                'Autorités' => implode("\n",array_map(function (AgentAutorite $a) { return $a->getAutorite()->getDenomination(); },$agent->getAutorites())),
                'Responsable d\'entretien' => '',
                'Validation du responsable' => '',
                'Validation de l\'autorité' => '',
                'Validation de l\'agent' => '',
            ];
        }

        foreach ($entretiens as $entretien) {
            if ($entretien->estNonHistorise()) {
                $resume[$entretien->getAgent()->getId()]['Responsable d\'entretien'] = $entretien->getResponsable()->getDenomination();
                $validationResponsable = $entretien->getValidationActiveByTypeCode(EntretienProfessionnelValidations::VALIDATION_RESPONSABLE);
                if ($validationResponsable) $resume[$entretien->getAgent()->getId()]['Validation du responsable'] = $validationResponsable->getHistoCreation()->format('d/m/Y à H:i');
                $validationAutorite = $entretien->getValidationActiveByTypeCode(EntretienProfessionnelValidations::VALIDATION_DRH);
                if ($validationAutorite) $resume[$entretien->getAgent()->getId()]['Validation de l\'autorité'] = $validationAutorite->getHistoCreation()->format('d/m/Y à H:i');
                $validationAgent = $entretien->getValidationActiveByTypeCode(EntretienProfessionnelValidations::VALIDATION_AGENT);
                if ($validationAgent) $resume[$entretien->getAgent()->getId()]['Validation de l\'agent'] = $validationAgent->getHistoCreation()->format('d/m/Y à H:i');
            }
        }

        $date = (new DateTime())->format('Ymd-His');
        $filename=$date. "_export_campagne_".str_replace('/','-',$campagne->getAnnee()).".csv";
        $CSV = new CsvModel();
        $CSV->setDelimiter(';');
        $CSV->setEnclosure('"');
        $CSV->setHeader($headers);
        $CSV->setData($resume);
        $CSV->setFilename($filename);
        return $CSV;
    }

    /** Page associée à la campagne dans l'écran des structures *******************************************************/

    /**
     * Action affichant une campagne d'entretien professionnel pour une structure
     * Attention : les agents doivent être complétement hydratés sinon les calculs d'affectations, de grades et d'obligation seront erronés
     */
    public function structureAction() : ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $selecteur = $this->getStructureService()->getStructuresByCurrentRole();

        if ($structure === null) { throw new RuntimeException("Aucune structure de trouvée."); }
        $structures = $this->getStructureService()->getStructuresFilles($structure, true);

        $agents = $this->getAgentService()->getAgentsByStructures($structures, $campagne->getDateDebut());
        $agents = $this->getAgentService()->filtrerWithStatutTemoin($agents, $this->getParametreService()->getParametreByCode(StructureParametres::TYPE, StructureParametres::AGENT_TEMOIN_STATUT),$campagne->getDateDebut());
        $agents = $this->getAgentService()->filtrerWithAffectationTemoin($agents, $this->getParametreService()->getParametreByCode(StructureParametres::TYPE, StructureParametres::AGENT_TEMOIN_AFFECTATION), $campagne->getDateDebut());
        $agents = $this->getAgentService()->filtrerWithEmploiTypeTemoin($agents, $this->getParametreService()->getParametreByCode(StructureParametres::TYPE, StructureParametres::AGENT_TEMOIN_EMPLOITYPE), $campagne->getDateDebut());
        // tri assumé par datatable ...

        $dateMinEnPoste = (DateTime::createFromFormat('d/m/Y', $campagne->getDateFin()->format('d/m/Y')))->sub(new DateInterval('P12M'));
        [$obligatoires, $facultatifs, $raison] = $this->getCampagneService()->trierAgents($campagne, $agents);

        $entretiens = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($campagne, $agents);
        $finalises = [];
        $encours = [];
        foreach ($entretiens as $entretien) {
            if ($entretien->isEtatActif(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT)) {
                $finalises[] = $entretien;
            } else {
                $encours[] = $entretien;
            }
        }

        $last =  $this->getCampagneService()->getLastCampagne();
        $campagnes =  $this->getCampagneService()->getCampagnesActives();
        if ($last !== null) $campagnes[] = $last;
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
            'raison' => $raison,
        ]);
    }

    public function superieurAction() : ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $responsable = $this->getAgentService()->getRequestedAgent($this);

        if ($responsable === null) {
            $user = $this->getUserService()->getConnectedUser();
            $responsable = $this->getAgentService()->getAgentByUser($user);
        }
        if ($responsable === null) {
            throw new RuntimeException("EntretienController::superieurAction() > Aucun responsable de fourni.");
        }
        
        $agents = $this->getAgentSuperieurService()->getAgentsWithSuperieur($responsable, $campagne->getDateDebut(), $campagne->getDateFin());
        $agents = $this->getAgentService()->filtrerWithStatutTemoin($agents, $this->getParametreService()->getParametreByCode(StructureParametres::TYPE, StructureParametres::AGENT_TEMOIN_STATUT),$campagne->getDateDebut());
        $agents = $this->getAgentService()->filtrerWithAffectationTemoin($agents, $this->getParametreService()->getParametreByCode(StructureParametres::TYPE, StructureParametres::AGENT_TEMOIN_AFFECTATION), $campagne->getDateDebut());

        //$entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnelsByCampagne($campagne, false, false);
        $entretiensS = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($campagne, $agents, false, false);
        $entretiensR = $this->getEntretienProfessionnelService()->getEntretiensProfessionnelsByResponsableAndCampagne($responsable, $campagne, false, false);

        $entretiens = [];
        foreach ($entretiensR as $entretien) { $entretiens[$entretien->getAgent()->getId()] = $entretien; }
        foreach ($entretiensS as $entretien) { $entretiens[$entretien->getAgent()->getId()] = $entretien; }

        $vm = new ViewModel([
            'campagne' => $campagne,
            'agent' => $responsable,
            'agents' => $agents,
            'entretiens' => $entretiens,
        ]);
        $vm->setTemplate('entretien-professionnel/campagne/entretien');
        return $vm;
    }

    public function autoriteAction() : ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $responsable = $this->getAgentService()->getRequestedAgent($this);

        if ($responsable === null) {
            $user = $this->getUserService()->getConnectedUser();
            $responsable = $this->getAgentService()->getAgentByUser($user);
        }
        if ($responsable === null) {
            throw new RuntimeException("EntretienController::superieurAction() > Aucun responsable de fourni.");
        }

        $agents = $this->getAgentAutoriteService()->getAgentsWithAutorite($responsable, $campagne->getDateDebut(), $campagne->getDateFin());
        $agents = $this->getAgentService()->filtrerWithStatutTemoin($agents, $this->getParametreService()->getParametreByCode(StructureParametres::TYPE, StructureParametres::AGENT_TEMOIN_STATUT),$campagne->getDateDebut());
        $agents = $this->getAgentService()->filtrerWithAffectationTemoin($agents, $this->getParametreService()->getParametreByCode(StructureParametres::TYPE, StructureParametres::AGENT_TEMOIN_AFFECTATION), $campagne->getDateDebut());

        $entretiens = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($campagne, $agents, false, false);

        $vm = new ViewModel([
            'campagne' => $campagne,
            'agent' => $responsable,
            'agents' => $agents,
            'entretiens' => $entretiens,
        ]);
        $vm->setTemplate('entretien-professionnel/campagne/entretien');
        return $vm;
    }
}
