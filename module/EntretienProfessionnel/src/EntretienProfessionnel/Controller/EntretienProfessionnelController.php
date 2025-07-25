<?php

namespace EntretienProfessionnel\Controller;

use Application\Controller\IndexController;
use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use DateInterval;
use DateTime;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Form\EntretienProfessionnel\EntretienProfessionnelFormAwareTrait;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Provider\Parametre\EntretienProfessionnelParametres;
use EntretienProfessionnel\Provider\Role\RoleProvider;
use EntretienProfessionnel\Provider\Template\PdfTemplates;
use EntretienProfessionnel\Provider\Validation\EntretienProfessionnelValidations;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Evenement\RappelEntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Evenement\RappelPasObservationServiceAwareTrait;
use EntretienProfessionnel\Service\Notification\NotificationServiceAwareTrait;
use EntretienProfessionnel\Service\Observateur\ObservateurServiceAwareTrait;
use Exception;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use RuntimeException;
use Structure\Provider\Role\RoleProvider as StructureRoleProvider;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenAutoform\Service\Formulaire\FormulaireInstanceServiceAwareTrait;
use UnicaenEtat\Service\EtatInstance\EtatInstanceServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenEvenement\Service\Evenement\EvenementServiceAwareTrait;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenPdf\Exporter\PdfExporter;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */
class EntretienProfessionnelController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use RenduServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use EvenementServiceAwareTrait;
    use EtatInstanceServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use MailServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use ObservateurServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use UserServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;
    use RappelEntretienProfessionnelServiceAwareTrait;
    use RappelPasObservationServiceAwareTrait;
    use StructureServiceAwareTrait;
    use FormulaireInstanceServiceAwareTrait;

    use EntretienProfessionnelFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $params = $this->params()->fromQuery();
        $entretiens = [];
        if ($params !== null and !empty($params)) {
            $entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnelsWithFiltre($params);
        }

        return new ViewModel([
            'entretiens' => $entretiens,
            'params' => $params,
            'campagnes' => $this->getCampagneService()->getCampagnes(),
            'etats' => $this->getEtatTypeService()->getEtatsTypesByCategorieCode('ENTRETIEN_PROFESSIONNEL'),
        ]);
    }

    public function indexAgentAction(): ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);

        $entretiens = [];
        if ($agent !== null) {
            $entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnelsByAgent($agent);
        }

        try {
            $intranet = $this->getParametreService()->getValeurForParametre(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::INTRANET_DOCUMENT);
        } catch (Exception $e) {
            throw new RuntimeException("Une erreur est survenue lors de la récupération du paramètre [" . EntretienProfessionnelParametres::TYPE . "|" . EntretienProfessionnelParametres::INTRANET_DOCUMENT . "]", 0, $e);
        }

        return new ViewModel([
            'entretiens' => $entretiens,
            'intranet' => $intranet,
        ]);
    }

    /** Action de recherche parmi les entretiens professionnels *******************************************************/

    public function rechercherResponsableAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $responsables = $this->getEntretienProfessionnelService()->findResponsableByTerm($term);
            $result = $this->getAgentService()->formatAgentJSON($responsables);
            return new JsonModel($result);
        }
        exit;
    }

    public function rechercherAgentAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getEntretienProfessionnelService()->findAgentByTerm($term);
            $result = $this->getAgentService()->formatAgentJSON($agents);
            return new JsonModel($result);
        }
        exit;
    }

    public function rechercherStructureAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $structures = $this->getEntretienProfessionnelService()->findStructureByTerm($term);
            $result = $this->getStructureService()->formatStructureJSON($structures);
            return new JsonModel($result);
        }
        exit;
    }

    /** Gestion des entretiens professionnels *************************************************************************/

    public function creerAction(): ViewModel|Response
    {
        // From route
        $structureId = $this->params()->fromQuery('structure');
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        if ($campagne === null) {
            throw new RuntimeException("Aucune campagne d'entretien professionnel de selectionnée", null, null);
        }

        // From Query
        $agentId = $this->params()->fromQuery('agent');
        $agent = ($agentId) ? $this->getAgentService()->getAgent($agentId) : null;

        // ne pas dupliquer les entretiens (s'il existe, alors on l'affiche).
        $entretien = null;
        if ($agent !== null) $entretien = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByAgentAndCampagne($agent, $campagne);
        if ($entretien !== null) {
            /** @see EntretienProfessionnelController::accederAction() */
            return $this->redirect()->toRoute('entretien-professionnel/acceder', ["entretien-professionnel" => $entretien->getId()], [], true);
        }

        $superieurs = array_map(function (AgentSuperieur $a) {
            return $a->getSuperieur();
        }, $agent->getSuperieurs());
        $entretien = new EntretienProfessionnel();
        $entretien->setCampagne($campagne);
        $entretien->setAgent($agent);
        if (count($superieurs) === 1) $entretien->setResponsable(current($superieurs));

        $form = $this->getEntretienProfessionnelForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/creer', ["campagne" => $campagne->getId()], ["query" => ["agent" => $agentId, "structure" => $structureId]], true));
        $form->setSuperieurs($superieurs);
        $form->init();
        $form->bind($entretien);

        if (isset($agent)) {
            $element = $form->get('agent');
            $element->setAttribute('readonly', true);
        }

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $delai = $this->getParametreService()->getValeurForParametre(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::DELAI_CONVOCATION_AGENT);
                $jplus15 = (new DateTime())->add(new DateInterval('P'.((string) $delai).'D'));                $this->flashMessenger()->addSuccessMessage("Entretien professionnel de <strong>" . $entretien->getAgent()->getDenomination() . "</strong> est bien planifié.");
                if ($entretien->getDateEntretien() < $jplus15) {
                    $this->flashMessenger()->addWarningMessage("<strong>Attention le délai de ".$delai." jours n'est pas respecté.</strong><br/> Veuillez-vous assurer que votre agent est bien d'accord avec les dates d'entretien professionnel.");
                }
                $this->getEntretienProfessionnelService()->initialiser($entretien);
                $this->getEtatInstanceService()->setEtatActif($entretien, EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTATION);
                $this->getEntretienProfessionnelService()->update($entretien);
                $this->getNotificationService()->triggerConvocationDemande($entretien);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Ajout d\'un nouvel entretien professionnel pour la campagne '.$campagne->getAnnee(),
            'form' => $form,
            'js' => "$('#campagne').parent().hide();",
        ]);
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');

        $agent = $entretien->getAgent();
        $superieurs = array_map(function (AgentSuperieur $a) {
            return $a->getSuperieur();
        }, $agent->getSuperieurs());

        $form = $this->getEntretienProfessionnelForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/modifier', ['entretien' => $entretien->getId()], [], true));
        $form->setSuperieurs($superieurs);
        $form->init();
        $form->bind($entretien);

        $element = $form->get('agent');
        $element->setAttribute('readonly', true);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $delai = $this->getParametreService()->getValeurForParametre(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::DELAI_CONVOCATION_AGENT);
                if ($delai !== null) {
                    $jplus15 = (new DateTime())->add(new DateInterval('P' . ((string)$delai) . 'D'));
                    $this->flashMessenger()->addSuccessMessage("Entretien professionnel de <strong>" . $entretien->getAgent()->getDenomination() . "</strong> est bien planifié.");
                    if ($entretien->getDateEntretien() < $jplus15) {
                        $this->flashMessenger()->addWarningMessage("<strong>Attention le délai de " . $delai . " jours n'est pas respecté.</strong><br/> Veuillez-vous assurer que votre agent est bien d'accord avec les dates d'entretien professionnel.");
                    }
                }
                $this->getEtatInstanceService()->setEtatActif($entretien, EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTATION);
                $this->getEntretienProfessionnelService()->generateToken($entretien);
                $this->getEntretienProfessionnelService()->update($entretien);

                $this->getNotificationService()->triggerConvocationDemande($entretien);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Modification d\'un entretien professionnel professionnel',
            'form' => $form,
        ]);
        return $vm;
    }

    public function accederAction(): ViewModel
    {
        $fromStructure = $this->params()->fromQuery('from-structure');

        //$this->getUserService()->selectRolePrefere($this);
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this);
        $agent = $entretien->getAgent();
        $ficheposte = $agent->getFichePosteBest();
        $fichesmetiers = [];
        $mails = $this->getMailService()->getMailsByMotClef($entretien->generateTag());

        $fiches = ($ficheposte) ? $ficheposte->getFichesMetiers() : [];
        foreach ($fiches as $fiche) {
            $fichesmetiers[] = $fiche->getFicheType();
        }

        //recupération de la fiche de poste (version fichier si elle existe)
        $fichiers = $agent->getFichiersByCode('FICHE_POSTE');
        $ficheposteFichier = null;
        if (!empty($fichiers)) {
            foreach ($fichiers as $fichier) {
                if ($ficheposteFichier === null OR $fichier->getHistoCreation() > $ficheposteFichier->getHistoCreation()) $ficheposteFichier = $fichier;
            }
        }

        $superieures = array_map(function (AgentSuperieur $a) {
            return $a->getSuperieur();
        }, $this->getAgentSuperieurService()->getAgentsSuperieursByAgent($agent));
        $autorites = array_map(function (AgentAutorite $a) {
            return $a->getAutorite();
        }, $this->getAgentAutoriteService()->getAgentsAutoritesByAgent($agent));

        $crep = $this->getFormulaireInstanceService()->getFormulaireInstance($entretien->getFormulaireInstance()->getId());
        $cref = $this->getFormulaireInstanceService()->getFormulaireInstance($entretien->getFormationInstance()->getId());
        $observateurs = $this->getObservateurService()->getObservateursByEntretienProfessionnel($entretien);

        return new ViewModel([
            'entretien' => $entretien,

            'user' => $this->getUserService()->getConnectedUser(),
            'role' => $this->getUserService()->getConnectedRole(),
            'agent' => $agent,
            'superieures' => $superieures,
            'autorites' => $autorites,

            'ficheposte' => $ficheposte,
            'ficheposteFichier' => $ficheposteFichier,

            'fichesmetiers' => $fichesmetiers,
            'mails' => $mails,
            'documents' => $this->getEntretienProfessionnelService()->getDocumentsUtiles(),

            'crep' => $crep,
            'cref' => $cref,
            'observateurs' => $observateurs,

            'BLOCAGE_COMPTERENDU' => $this->getParametreService()->getValeurForParametre(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::CAMPAGNE_BLOCAGE_STRICT_MODIFICATION),
            'BLOCAGE_VALIDATION' => $this->getParametreService()->getValeurForParametre(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::CAMPAGNE_BLOCAGE_STRICT_VALIDATION),
            'OBSERVATION_FINALE' => $this->getParametreService()->getValeurForParametre(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::OBSERVATION_AGENT_FINAL),

            'fromStructure' => $fromStructure,
        ]);
    }

    public function historiserAction(): ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getEntretienProfessionnelService()->historise($entretien);
                $this->getNotificationService()->triggerAnnulationEntretienProfessionnel($entretien);
            }
            exit();
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/confirmation');
        if ($entretien !== null) {
            $vm->setVariables([
                'title' => "Historisation de l'entretien professionnel de " . $entretien->getAgent()->getDenomination() . " en date du " . $entretien->getDateEntretien()->format('d/m/Y'),
                'text' => "L'historisation masquera et annulera cet entretien professionnel. Êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/historiser', ["entretien" => $entretien->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function restaurerAction(): Response
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $this->getEntretienProfessionnelService()->restore($entretien);
        $this->getNotificationService()->triggerConvocationDemande($entretien);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('entretien-professionnel', [], [], true);
    }

    public function detruireAction(): ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                foreach ($entretien->getEvenements() as $evenement) {
                    $this->getEvenementService()->supprimer($evenement);
                    $this->getNotificationService()->triggerAnnulationEntretienProfessionnel($entretien);
                }
                $this->getEntretienProfessionnelService()->delete($entretien);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($entretien !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'entretien professionnel de " . $entretien->getAgent()->getDenomination() . " en date du " . $entretien->getDateEntretien()->format('d/m/Y'),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/detruire', ["entretien" => $entretien->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Validation élément associée à l'agent *************************************************************************/

    public function validerElementAction(): ViewModel
    {
        $type = $this->params()->fromRoute('type');
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $validation = $entretien->getValidationActiveByTypeCode($type);
            if ($validation === null) {
                if ($data["reponse"] === "oui") {
                    $this->getValidationInstanceService()->setValidationActive($entretien, $type);
                    $this->getEntretienProfessionnelService()->update($entretien);
                }
                if ($data["reponse"] === "non") {
                    $this->getValidationInstanceService()->setValidationActive($entretien, $type, 'Refus');
                    $this->getEntretienProfessionnelService()->update($entretien);
                }
            }
            switch ($type) {
                case EntretienProfessionnelValidations::VALIDATION_RESPONSABLE :
                    $this->getEtatInstanceService()->setEtatActif($entretien, EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_RESPONSABLE);
                    $this->getEntretienProfessionnelService()->update($entretien);
                    $this->getNotificationService()->triggerValidationResponsableEntretien($entretien);
                    $dateNotification = (new DateTime())->add(new DateInterval('P1W'));
                    $this->getRappelPasObservationService()->creer($entretien, $dateNotification);
                    $this->getEntretienProfessionnelService()->update($entretien);
                    break;

                case EntretienProfessionnelValidations::VALIDATION_OBSERVATION:
                    $this->getEtatInstanceService()->setEtatActif($entretien, EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_OBSERVATION);
                    $this->getEntretienProfessionnelService()->update($entretien);
                    $this->getNotificationService()->triggerObservations($entretien);
                    break;

                case EntretienProfessionnelValidations::VALIDATION_DRH :
                    $this->getEtatInstanceService()->setEtatActif($entretien, EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_HIERARCHIE);
                    $this->getEntretienProfessionnelService()->update($entretien);
                    $this->getNotificationService()->triggerValidationResponsableHierarchique($entretien);
                    break;

                case EntretienProfessionnelValidations::VALIDATION_AGENT :
                    $this->getEtatInstanceService()->setEtatActif($entretien, EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT);
                    $this->getEntretienProfessionnelService()->update($entretien);
                    $this->getNotificationService()->triggerValidationAgent($entretien);
                    break;
            }
            exit();
        }

        $title = "Validation de l'entretien";
        $text = "Validation de l'entretien";
        switch ($type) {
            case EntretienProfessionnelValidations::VALIDATION_RESPONSABLE :
                $title = "Validation par le responsable de l'entretien professionnel";
                $text = "<p>";
                $text .= "Cette validation figera les comptes-rendus d'entretien et de formation de " . $entretien->getAgent()->getDenomination(true) . ".<br>";
                $text .= "La validation ouvre la période de huit jours pour l'expression des observations et notifie " . $entretien->getAgent()->getDenomination(true) . ".";
                $text .= "</p>";
                $text .= "Êtes-vous sur·e de vouloir valider ?";
                break;
            case EntretienProfessionnelValidations::VALIDATION_OBSERVATION:
                $title = "Validation des observations (ou de l'absence de observations) de l'agent·e";
                $text = "<p>";
                $text .= "Cette validation figera les observations si elles ont été faites.<br>";
                $text .= "La validation ouvre la possibilité aux autorités hiérachiques de valider à leur tour cet entretien.";
                $text .= "</p>";
                $text .= "Êtes-vous sur·e de vouloir valider ?";
                break;
            case EntretienProfessionnelValidations::VALIDATION_DRH :
                $title = "Validation de l'autorité hiérarchique";
                $text = "<p>";
                $text .= "Cette validation vaut pour visa de la lecture de l'entretien professionnel de " . $entretien->getAgent()->getDenomination(true) . ".<br>";
                $text .= "La validation ouvre la possibilité à " . $entretien->getAgent()->getDenomination(true) . " de finaliser son entretien.";
                $text .= "</p>";
                $text .= "Êtes-vous sur·e de vouloir valider ?";
                break;
            case EntretienProfessionnelValidations::VALIDATION_AGENT :
                $title = "Validation de l'agent·e";
                $text = "<p>";
                $text .= "Cette validation finalise l'entretien professionnel de " . $entretien->getAgent()->getDenomination(true) . ".";
                $text .= "</p>";
                $text .= "Êtes-vous sur·e de vouloir valider ?";
                break;
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-validation/validation-instance/validation-modal');
        $vm->setVariables([
            'action' => $this->url()->fromRoute('entretien-professionnel/valider-element', ["type" => $type, "entretien" => $entretien->getId()], [], true),
            'title' => $title,
            'text' => $text,
            'refus' => false,
        ]);
        return $vm;
    }

    public function revoquerValidationAction(): Response
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this);
        $validation = $this->getValidationInstanceService()->getRequestedValidationInstance($this);
        $this->getValidationInstanceService()->historise($validation);

        if ($validation->getType()->getCode() === EntretienProfessionnelValidations::VALIDATION_RESPONSABLE) {
            $this->getEtatInstanceService()->setEtatActif($entretien, EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER);
        }
        if ($validation->getType()->getCode() === EntretienProfessionnelValidations::VALIDATION_OBSERVATION) {
            $this->getEtatInstanceService()->setEtatActif($entretien, EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_RESPONSABLE);
        }
        if ($validation->getType()->getCode() === EntretienProfessionnelValidations::VALIDATION_DRH) {
            if ($entretien->getValidationActiveByTypeCode(EntretienProfessionnelValidations::VALIDATION_OBSERVATION) !== null) {
                $this->getEtatInstanceService()->setEtatActif($entretien, EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_OBSERVATION);
            } else {
                $this->getEtatInstanceService()->setEtatActif($entretien, EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_RESPONSABLE);
            }
        }
        if ($validation->getType()->getCode() === EntretienProfessionnelValidations::VALIDATION_AGENT) {
            $this->getEtatInstanceService()->setEtatActif($entretien, EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_HIERARCHIE);
        }

        $this->getEntretienProfessionnelService()->update($entretien);


        /** @see EntretienProfessionnelController::accederAction */
        return $this->redirect()->toRoute('entretien-professionnel/acceder', ['entretien-professionnel' => $entretien->getId()], ['fragment' => 'validation'], true);
    }

    public function exporterCrepAction(): string
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $vars = [
            'entretien' => $entretien,
            'agent' => $entretien->getAgent(),
            'campagne' => $entretien->getCampagne(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(PdfTemplates::CREP, $vars);
        return PdfExporter::generatePdf($rendu->getSujet(), $rendu->getSujet(), $rendu->getCorps());
    }

    public function exporterCrefAction(): string
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $vars = [
            'entretien' => $entretien,
            'agent' => $entretien->getAgent(),
            'campagne' => $entretien->getCampagne(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(PdfTemplates::CREF, $vars);
        return PdfExporter::generatePdf($rendu->getSujet(), $rendu->getSujet(), $rendu->getCorps());
    }

    public function accepterEntretienAction(): ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this);
        $token = $this->params()->fromRoute('token');
        if ($entretien === null) throw new RuntimeException("Aucun entretien professionnel de remonté pour l'id #" . $this->params()->fromRoute('entretien-professionnel'));

        if (!$entretien->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTATION)) {
            $vm = new ViewModel(['entretien' => $entretien]);
            $vm->setTemplate('entretien-professionnel/accepter-etat-incompatible');
            return $vm;
        }

        try {
            $delai = $this->getParametreService()->getValeurForParametre(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::DELAI_ACCEPTATION_AGENT);
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération d'un paramètre",0,$e);
        }
        try {
            $dateButoir = (DateTime::createFromFormat('d/m/Y', $entretien->getHistoModification()->format('d/m/Y')))->add(new DateInterval('P' . $delai . 'D'));
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenu lors du calcul de la date butoir", null, $e);
        }
        $depassee = $dateButoir < (new DateTime());

        if ($entretien->getToken() === $token) {
            $dateRappel = DateTime::createFromFormat('d/m/Y à H:i', $entretien->getDateEntretien()->format('d/m/Y à H:i'));
            $dateRappel = $dateRappel->sub(new DateInterval('P1W'));
            $this->getRappelEntretienProfessionnelService()->creer($entretien, $dateRappel);

            $entretien->setToken(null);
            $entretien->setAcceptation((new DateTime()));
            $this->getEtatInstanceService()->setEtatActif($entretien, EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER);
            $this->getEntretienProfessionnelService()->update($entretien);

            $this->getNotificationService()->triggerConvocationAcceptation($entretien);
        }

        return new ViewModel([
            'entretien' => $entretien,
            'token' => $token,
            'depassee' => $depassee,
            'role' => $this->getUserService()->getConnectedRole(),
        ]);
    }

    public function actionAction(): ViewModel
    {
        $this->layout()->setTemplate(false);
        $date = new DateTime();
//        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this);

        $vm = new ViewModel([
            'date' => $date,
//            'entretien' => $entretien,
        ]);
        return $vm;
    }

    public function retourListeAction(): Response
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this);
        $campagne = $entretien->getCampagne();
        $role = $this->getUserService()->getConnectedRole();
        $user = $this->getUserService()->getConnectedUser();

        $structureId = $this->params()->fromQuery('from-structure');
        $structure = $this->getStructureService()->getStructure($structureId);

        if ($role === null) return $this->redirect()->toRoute('home', [], [], true);

        switch($role->getRoleId()) {
            case AppRoleProvider::ADMIN_TECH :
            case AppRoleProvider::ADMIN_FONC :
            case AppRoleProvider::DRH:
            case AppRoleProvider::OBSERVATEUR :
                /** @see EntretienProfessionnelController::indexAction() */
                return $this->redirect()->toRoute('entretien-professionnel', [], ['query' => ['campagne' => $campagne->getId()]], true);
            case StructureRoleProvider::RESPONSABLE:
            case StructureRoleProvider::GESTIONNAIRE:
            case StructureRoleProvider::OBSERVATEUR:
                if ($structure) {
                    /** @see CampagneController::structureAction() */
                    return $this->redirect()->toRoute('entretien-professionnel/campagne/structure', ['structure' => $structure->getId(), 'campagne' => $campagne->getId()], true);
                }
                /** @see IndexController::indexAction() */
                return $this->redirect()->toRoute('home', [], [], true);
            case  Agent::ROLE_SUPERIEURE :
                /** @see IndexController::indexSuperieurAction() */
                return $this->redirect()->toRoute('index-superieur', [], ["fragment" => "entretien_". $campagne->getId()], true);
            case  Agent::ROLE_AUTORITE :
                /** @see IndexController::indexAutoriteAction() */
                return $this->redirect()->toRoute('index-autorite', [], ["fragment" => "entretien_". $campagne->getId()], true);
            case  Agent::ROLE_AGENT :
                /** @see EntretienProfessionnelController::indexAgentAction() */
                return $this->redirect()->toRoute('entretien-professionnel/index-agent', [], [], true);
            case  RoleProvider::OBSERVATEUR :
                /** @see IndexController::indexAction() */
                return $this->redirect()->toRoute('home', [], [], true);
            default:
                throw new RuntimeException("Rôle [".$role->getRoleId()."] non prévu");
        }
    }
}
