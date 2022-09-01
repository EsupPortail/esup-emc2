<?php

namespace EntretienProfessionnel\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use DateInterval;
use DateTime;
use Doctrine\ORM\ORMException;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnelConstant;
use EntretienProfessionnel\Form\EntretienProfessionnel\EntretienProfessionnelFormAwareTrait;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Provider\TemplateProvider;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Evenement\RappelEntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Evenement\RappelPasObservationServiceAwareTrait;
use EntretienProfessionnel\Service\Notification\NotificationServiceAwareTrait;
use Exception;
use Mpdf\MpdfException;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Form\Element\SearchAndSelect;
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
        $fromQueries  = $this->params()->fromQuery();
        $agent        = $this->getAgentService()->getAgent($fromQueries['agent']);
        $responsable  = $this->getAgentService()->getAgent($fromQueries['responsable']);
        $structure    = $this->getStructureService()->getStructure($fromQueries['structure']);
        $campagne     = $this->getCampagneService()->getCampagne((trim($fromQueries['campagne']) !== '')?trim($fromQueries['campagne']):null);
        $etat         = $this->getEtatService()->getEtat((trim($fromQueries['etat'])!=='')?trim($fromQueries['etat']):null);
        $entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnels($agent, $responsable, $structure, $campagne, $etat);

        $campagnes = $this->getCampagneService()->getCampagnes();
        $etats = $this->getEtatService()->getEtatsByTypeCode('ENTRETIEN_PROFESSIONNEL');

        return new ViewModel([
            'entretiens' => $entretiens,
            'campagnes' => $campagnes,
            'etats' => $etats,

            'params' => [
                'campagneId' => ($campagne)?$campagne->getId():null,
                'etatId' => ($etat)?$etat->getId():null,
                'agent' => $agent,
                'responsable' => $responsable,
                'structure' => $structure,
            ],
        ]);
    }

    public function indexDelegueAction() : ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);

        $entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnelsByDelegue($agent);

        return new ViewModel([
            'entretiens' => $entretiens,
        ]);
    }

    public function indexAgentAction() : ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);

        $entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnelsByAgent($agent);

        return new ViewModel([
            'entretiens' => $entretiens,
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

    public function findResponsablePourEntretienAction() : JsonModel
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);

        $agentId = $this->params()->fromQuery('agent');
        $agent = $this->getAgentService()->getAgent($agentId);


        $term = $this->params()->fromQuery('term');

        if ($term !== null and trim($term) !== "") {
            $agentsResponsables = ($structure)?$this->getEntretienProfessionnelService()->findResponsablePourEntretien($structure, $term):[];
            $agentsDelegues = ($campagne AND $structure)?$this->getEntretienProfessionnelService()->findDeleguePourEntretien($structure, $campagne, $term):[];
            $agentsSuperieures = ($agent)?$this->getEntretienProfessionnelService()->findSuperieurPourEntretien($agent, $term):[];
            $result = $this->getAgentService()->formatAgentJSON(array_merge($agentsResponsables, $agentsDelegues, $agentsSuperieures));
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

    public function creerAction()
    {
        // From route
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        if ($campagne === null) {
            throw new RuntimeException("Aucune campagne d'entretien professionnel de selectionnée", null, null);
        }

        // From Query
        $agentId = $this->params()->fromQuery('agent');
        $agent = ($agentId) ? $this->getAgentService()->getAgent($agentId) : null;
        $structureId = $this->params()->fromQuery('structure');
        $structure = ($structureId) ? $this->getStructureService()->getStructure($structureId) : null;

        // ne pas dupliquer les entretiens (si il existe alors on l'affiche)
        $entretien = null;
        if ($agent !== null) $entretien = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByAgentAndCampagne($agent, $campagne);
        if ($entretien !== null) {
            /** @see EntretienProfessionnelController::accederAction() */
            return $this->redirect()->toRoute('entretien-professionnel/acceder', ["entretien-professionnel" => $entretien->getId()], [], true);
        }

        $entretien = new EntretienProfessionnel();
        $entretien->setCampagne($campagne);
        if ($agent !== null) $entretien->setAgent($agent);

        $form = $this->getEntretienProfessionnelForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/creer', ["campagne" => $campagne->getId()], ["query" => ["agent" => $agentId, "structure" => $structureId]], true));
        $form->bind($entretien);

        if ($structure !== null) {
            /** @var SearchAndSelect $element */
            $element = $form->get('responsable');
            /** @see EntretienProfessionnelController::findResponsablePourEntretienAction() */
            $element->setAutocompleteSource($this->url()->fromRoute('entretien-professionnel/find-responsable-pour-entretien', ['agent' => $agent->getId(), 'structure' => $structure->getId(), 'campagne' => $campagne->getId()], ['query' => ['agent' => $agentId]], true));
        } else {
            /** @var SearchAndSelect $element */
            $element = $form->get('responsable');
            /** @see EntretienProfessionnelController::findResponsablePourEntretienAction() */
            $element->setAutocompleteSource($this->url()->fromRoute('entretien-professionnel/find-responsable-pour-entretien', [], ['query' => ['agent' => $agentId]], true));
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
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajout d\'un nouvel entretien professionnel ',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $campagne = $entretien->getCampagne();

        $agent = $entretien->getAgent();
        if ($structure === null) $structure = $agent->getAffectationPrincipale()->getStructure();

        $form = $this->getEntretienProfessionnelForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/modifier', ['entretien' => $entretien->getId()], [], true));
        $form->bind($entretien);
        /** @var SearchAndSelect $element */
        $element = $form->get('responsable');
        if ($structure !== null) {
            $element->setAutocompleteSource($this->url()->fromRoute('entretien-professionnel/find-responsable-pour-entretien', ['structure' => $structure->getId(), 'campagne' => $campagne->getId()], ["query" => ["agent" => $agent->getId()]], true));
        } else {
            $element->setAutocompleteSource($this->url()->fromRoute('entretien-professionnel/find-responsable-pour-entretien', [], ['query' => ['agent' => $agent->getId()]], true));
        }
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
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modification d\'un entretien professionnel professionnel',
            'form' => $form,
        ]);
        return $vm;
    }

    public function accederAction() : ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien-professionnel');
        $agent = $entretien->getAgent();
        $ficheposte = $agent->getFichePosteBest();
        $fichesmetiers = [];
        $mails = $this->getMailService()->getMailsByMotClef($entretien->generateTag());

        $fiches = ($ficheposte)?$ficheposte->getFichesMetiers():[];
        foreach ($fiches as $fiche) {
            $fichesmetiers[] = $fiche->getFicheType();
        }

        return new ViewModel([
            'entretien' => $entretien,

            'agent'         => $agent,
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
        return $this->redirect()->toRoute('entretien-professionnel', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $this->getEntretienProfessionnelService()->restore($entretien);
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
                    case EntretienProfessionnelConstant::VALIDATION_RESPONSABLE :
                        $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_RESPONSABLE));
                        $this->getEntretienProfessionnelService()->update($entretien);

                        $this->getNotificationService()->triggerValidationResponsableEntretien($entretien);

                        $dateNotification = (new DateTime())->add(new DateInterval('P1W'));
                        $this->getRappelPasObservationService()->creer($entretien, $dateNotification);
                        break;

                    case EntretienProfessionnelConstant::VALIDATION_OBSERVATION:
                        $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_OBSERVATION));
                        $this->getEntretienProfessionnelService()->update($entretien);

                        $this->getNotificationService()->triggerObservations($entretien);
                        break;

                    case EntretienProfessionnelConstant::VALIDATION_DRH :
                        $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_HIERARCHIE));
                        $this->getEntretienProfessionnelService()->update($entretien);

                        $this->getNotificationService()->triggerValidationResponsableHierarchique($entretien);
                        break;

                    case EntretienProfessionnelConstant::VALIDATION_AGENT :
                        $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT));
                        $this->getEntretienProfessionnelService()->update($entretien);
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

        if ($validation->getType()->getCode() === EntretienProfessionnelConstant::VALIDATION_RESPONSABLE) {
            $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER));
        }
        if ($validation->getType()->getCode() === EntretienProfessionnelConstant::VALIDATION_OBSERVATION) {
            $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_RESPONSABLE));
        }
        if ($validation->getType()->getCode() === EntretienProfessionnelConstant::VALIDATION_DRH) {
            if ($entity->getValidationByType(EntretienProfessionnelConstant::VALIDATION_OBSERVATION) !== null) {
                $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_OBSERVATION));
            } else {
                $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_RESPONSABLE));
            }
        }
        if ($validation->getType()->getCode() === EntretienProfessionnelConstant::VALIDATION_AGENT) {
            $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_HIERARCHIE));
        }

        try {
            $this->getValidationInstanceService()->getEntityManager()->flush($entity);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base.");
        }

        /** @see \EntretienProfessionnel\Controller\EntretienProfessionnelController::accederAction() */
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
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(TemplateProvider::CREP, $vars);

        try {
            $exporter = new PdfExporter();
            $exporter->getMpdf()->SetTitle($rendu->getSujet());
            $exporter->setHeaderScript('');
            $exporter->setFooterScript('');
            $exporter->addBodyHtml($rendu->getCorps());
            return $exporter->export($rendu->getSujet());
        } catch(MpdfException $e) {
            throw new RuntimeException("Un problème lié à MPDF est survenue",0,$e);
        }
    }

    public function exporterCrefAction() : string
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $formations = $this->getAgentService()->getFormationsSuiviesByAnnee($entretien->getAgent(), $entretien->getAnnee());
        $vars= [
            'entretien' => $entretien,
            'agent' => $entretien->getAgent(),
            'formations' => $formations,
            'campagne' => $entretien->getCampagne(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(TemplateProvider::CREF, $vars);

        try {
            $exporter = new PdfExporter();
            $exporter->getMpdf()->SetTitle($rendu->getSujet());
            $exporter->setHeaderScript('');
            $exporter->setFooterScript('');
            $exporter->addBodyHtml($rendu->getCorps());
            return $exporter->export($rendu->getSujet());
        } catch(MpdfException $e) {
            throw new RuntimeException("Un problème lié à MPDF est survenue",0,$e);
        }
        
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
