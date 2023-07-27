<?php

namespace EntretienProfessionnel\Controller;

use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use DateInterval;
use DateTime;
use Doctrine\ORM\Exception\ORMException;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Form\EntretienProfessionnel\EntretienProfessionnelFormAwareTrait;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Provider\Parametre\EntretienProfessionnelParametres;
use EntretienProfessionnel\Provider\Template\PdfTemplates;
use EntretienProfessionnel\Provider\Validation\EntretienProfessionnelValidations;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Evenement\RappelEntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Evenement\RappelPasObservationServiceAwareTrait;
use EntretienProfessionnel\Service\Notification\NotificationServiceAwareTrait;
use Exception;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenPdf\Exporter\PdfExporter;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

/** @method FlashMessenger flashMessenger() */

class EntretienProfessionnelController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use RenduServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use EtatServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use MailServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use UserServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;
    use RappelEntretienProfessionnelServiceAwareTrait;
    use RappelPasObservationServiceAwareTrait;
    use StructureServiceAwareTrait;

    use EntretienProfessionnelFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $params  = $this->params()->fromQuery();
        $entretiens = [];
        if ($params !== null AND !empty($params)) {
            $entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnelsWithFiltre($params);
        }

        return new ViewModel([
            'entretiens' => $entretiens,
            'params' => $params,
            'campagnes' => $this->getCampagneService()->getCampagnes(),
            'etats' => $this->getEtatService()->getEtatsByTypeCode('ENTRETIEN_PROFESSIONNEL'),
        ]);
    }

    public function indexAgentAction() : ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);

        $entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnelsByAgent($agent);
        try {
            $intranet = $this->getParametreService()->getValeurForParametre(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::INTRANET_DOCUMENT);
        } catch (Exception $e) {
            throw new RuntimeException("Une erreur est survenue lors de la récupération du paramètre [".EntretienProfessionnelParametres::TYPE."|".EntretienProfessionnelParametres::INTRANET_DOCUMENT."]",0,$e);
        }

        return new ViewModel([
            'entretiens' => $entretiens,
            'intranet' => $intranet,
        ]);
    }

    /** Action de recherche parmi les entretiens professionnels *******************************************************/

    public function rechercherResponsableAction() : JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $responsables = $this->getEntretienProfessionnelService()->findResponsableByTerm($term);
            $result = $this->getAgentService()->formatAgentJSON($responsables);
            return new JsonModel($result);
        }
        exit;
    }

    public function rechercherAgentAction() : JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getEntretienProfessionnelService()->findAgentByTerm($term);
            $result = $this->getAgentService()->formatAgentJSON($agents);
            return new JsonModel($result);
        }
        exit;
    }

    public function rechercherStructureAction() : JsonModel
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

        $superieurs = array_map(function (AgentSuperieur $a) { return $a->getSuperieur(); }, $agent->getSuperieurs());
        $entretien = new EntretienProfessionnel();
        $entretien->setCampagne($campagne);
        $entretien->setAgent($agent);
        if (count($superieurs) === 1) $entretien->setResponsable(current($superieurs));

        $form = $this->getEntretienProfessionnelForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/creer', ["campagne" => $campagne->getId()], ["query" => ["agent" => $agentId, "structure" => $structureId]], true));
        $form->setSuperieurs($superieurs);
        $form->init();
        $form->bind($entretien);

        if ($agent !== null) {
            $element = $form->get('agent');
            $element->setAttribute('readonly', true);
        }

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $jplus15 = (new DateTime())->add(new DateInterval('P15D'));
                $this->flashMessenger()->addSuccessMessage("Entretien profesionnel de <strong>".$entretien->getAgent()->getDenomination()."</strong> est bien planifié.");
                if ($entretien->getDateEntretien() < $jplus15 ) {
                    $this->flashMessenger()->addWarningMessage("<strong>Attention le délai de 15 jours n'est pas respecté.</strong><br/> Veuillez-vous assurer que votre agent est bien d'accord avec les dates d'entretien professionnel.");
                }
               $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTATION));
               $this->getEntretienProfessionnelService()->initialiser($entretien);
               $this->getNotificationService()->triggerConvocationDemande($entretien);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Ajout d\'un nouvel entretien professionnel ',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');

        $agent = $entretien->getAgent();
        $superieurs = array_map(function (AgentSuperieur $a) { return $a->getSuperieur(); }, $agent->getSuperieurs());

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
                $jplus15 = (new DateTime())->add(new DateInterval('P15D'));
                $this->flashMessenger()->addSuccessMessage("Entretien profesionnel de <strong>".$entretien->getAgent()->getDenomination()."</strong> est bien planifié.");
                if ($entretien->getDateEntretien() < $jplus15 ) {
                    $this->flashMessenger()->addWarningMessage("<strong>Attention le délai de 15 jours n'est pas respecté.</strong><br/> Veuillez-vous assurer que votre agent est bien d'accord avec les dates d'entretien professionnel.");
                }

                $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTATION));
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

    public function accederAction() : ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this);
        $agent = $entretien->getAgent();
        $ficheposte = $agent->getFichePosteBest();
        $fichesmetiers = [];
        $mails = $this->getMailService()->getMailsByMotClef($entretien->generateTag());

        $fiches = ($ficheposte)?$ficheposte->getFichesMetiers():[];
        foreach ($fiches as $fiche) {
            $fichesmetiers[] = $fiche->getFicheType();
        }

        $superieures    = array_map(function (AgentSuperieur $a) { return $a->getSuperieur(); }, $this->getAgentSuperieurService()->getAgentsSuperieursByAgent($agent));
        $autorites      = array_map(function (AgentAutorite $a)  { return $a->getAutorite(); }, $this->getAgentAutoriteService()->getAgentsAutoritesByAgent($agent));

        return new ViewModel([
            'entretien' => $entretien,

            'agent'         => $agent,
            'superieures'   => $superieures,
            'autorites'     => $autorites,

            'ficheposte'    => $ficheposte,
            'fichesmetiers' => $fichesmetiers,
            'connectedUser' => $this->getUserService()->getConnectedUser(),
            'mails'         => $mails,
            'documents'     => $this->getEntretienProfessionnelService()->getDocumentsUtiles(),
        ]);
    }

    public function historiserAction() : Response
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $this->getEntretienProfessionnelService()->historise($entretien);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('entretien-professionnel', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $this->getEntretienProfessionnelService()->restore($entretien);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('entretien-professionnel', [], [], true);
    }

    public function detruireAction() : ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getEntretienProfessionnelService()->delete($entretien);
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

    public function validerElementAction() : ViewModel
    {
        $type = $this->params()->fromRoute('type');
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $validation = $entretien->getValidationByType($type);
            if ($validation === null) {
                if ($data["reponse"] === "oui") $validation = $this->getEntretienProfessionnelService()->addValidation($type, $entretien);
                if ($data["reponse"] === "non") $validation = $this->getEntretienProfessionnelService()->addValidation($type, $entretien, 'Refus');
            }
            if ($validation !== null) {
                switch ($type) {
                    case EntretienProfessionnelValidations::VALIDATION_RESPONSABLE :
                        $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_RESPONSABLE));
                        $this->getEntretienProfessionnelService()->update($entretien);

                        $this->getNotificationService()->triggerValidationResponsableEntretien($entretien);

                        $dateNotification = (new DateTime())->add(new DateInterval('P1W'));
                        $this->getRappelPasObservationService()->creer($entretien, $dateNotification);
                        break;

                    case EntretienProfessionnelValidations::VALIDATION_OBSERVATION:
                        $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_OBSERVATION));
                        $this->getEntretienProfessionnelService()->update($entretien);

                        $this->getNotificationService()->triggerObservations($entretien);
                        break;

                    case EntretienProfessionnelValidations::VALIDATION_DRH :
                        $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_HIERARCHIE));
                        $this->getEntretienProfessionnelService()->update($entretien);

                        $this->getNotificationService()->triggerValidationResponsableHierarchique($entretien);
                        break;

                    case EntretienProfessionnelValidations::VALIDATION_AGENT :
                        $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT));
                        $this->getEntretienProfessionnelService()->update($entretien);

                        $this->getNotificationService()->triggerValidationAgent($entretien);
                        break;
                }
            }
            exit();
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-validation/validation-instance/validation-modal');
        $vm->setVariables([
            'title' => "Validation de l'entretien",
            'text' => "Validation de l'entretien",
            'action' => $this->url()->fromRoute('entretien-professionnel/valider-element', ["type" => $type, "entretien" => $entretien->getId()], [], true),
            'refus' => false,
        ]);
        return $vm;
    }

    public function revoquerValidationAction() : Response
    {
        $validation = $this->getValidationInstanceService()->getRequestedValidationInstance($this);
        $this->getValidationInstanceService()->historise($validation);


        /** @var EntretienProfessionnel $entity */
        $entity = $this->getValidationInstanceService()->getEntity($validation);

        if ($validation->getType()->getCode() === EntretienProfessionnelValidations::VALIDATION_RESPONSABLE) {
            $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER));
        }
        if ($validation->getType()->getCode() === EntretienProfessionnelValidations::VALIDATION_OBSERVATION) {
            $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_RESPONSABLE));
        }
        if ($validation->getType()->getCode() === EntretienProfessionnelValidations::VALIDATION_DRH) {
            if ($entity->getValidationByType(EntretienProfessionnelValidations::VALIDATION_OBSERVATION) !== null) {
                $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_OBSERVATION));
            } else {
                $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_RESPONSABLE));
            }
        }
        if ($validation->getType()->getCode() === EntretienProfessionnelValidations::VALIDATION_AGENT) {
            $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_HIERARCHIE));
        }

        try {
            $this->getValidationInstanceService()->getEntityManager()->flush($entity);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base.",0, $e);
        }

        /** @see EntretienProfessionnelController::accederAction */
        return $this->redirect()->toRoute('entretien-professionnel/acceder', ['entretien-professionnel' => $entity->getId()], ['fragment' => 'validation'], true);
    }

    public function exporterCrepAction() : string
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $vars= [
            'entretien' => $entretien,
            'agent' => $entretien->getAgent(),
            'campagne' => $entretien->getCampagne(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(PdfTemplates::CREP, $vars);
        return PdfExporter::generatePdf($rendu->getSujet(), $rendu->getSujet(), $rendu->getCorps());
    }

    public function exporterCrefAction() : string
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $formations = $this->getAgentService()->getFormationsSuiviesByAnnee($entretien->getAgent(), $entretien->getCampagne()->getAnnee());
        $vars= [
            'entretien' => $entretien,
            'agent' => $entretien->getAgent(),
            'formations' => $formations,
            'campagne' => $entretien->getCampagne(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(PdfTemplates::CREF, $vars);
        return PdfExporter::generatePdf($rendu->getSujet(), $rendu->getSujet(), $rendu->getCorps());
    }

    public function accepterEntretienAction() : ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this);
        $token = $this->params()->fromRoute('token');
        if ($entretien === null) throw new RuntimeException("Aucun entretien professionnel de remonté pour l'id #".$this->params()->fromRoute('entretien-professionnel'));

        $delai = $this->getParametreService()->getParametreByCode('ENTRETIEN_PROFESSIONNEL','DELAI_ACCEPTATION_AGENT')->getValeur();
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
            $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER));
            $this->getEntretienProfessionnelService()->update($entretien);

            $this->getNotificationService()->triggerConvocationAcceptation($entretien);
        }

        return new ViewModel([
            'entretien' => $entretien,
            'token' => $token,
            'depassee' => $depassee,
        ]);
    }

    public function actionAction() : ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this);

        $vm =  new ViewModel([
            'entretien' => $entretien,
        ]);
        return $vm;
    }

}
