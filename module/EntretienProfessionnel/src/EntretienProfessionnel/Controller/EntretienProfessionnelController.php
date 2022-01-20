<?php

namespace EntretienProfessionnel\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceAwareTrait;
use Application\Service\RendererAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use DateInterval;
use DateTime;
use Doctrine\ORM\ORMException;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnelConstant;
use EntretienProfessionnel\Form\Campagne\CampagneFormAwareTrait;
use EntretienProfessionnel\Form\EntretienProfessionnel\EntretienProfessionnelFormAwareTrait;
use EntretienProfessionnel\Form\Observation\ObservationFormAwareTrait;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Evenement\RappelEntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Notification\NotificationServiceAwareTrait;
use EntretienProfessionnel\Service\Observation\ObservationServiceAwareTrait;
use Exception;
use Mpdf\MpdfException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenPdf\Exporter\PdfExporter;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;
use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class EntretienProfessionnelController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use ConfigurationServiceAwareTrait;
    use RenduServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use EtatServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use ObservationServiceAwareTrait;
    use MailServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use ParcoursDeFormationServiceAwareTrait;
    use UserServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;
    use ValidationTypeServiceAwareTrait;
    use RappelEntretienProfessionnelServiceAwareTrait;
    use StructureServiceAwareTrait;

    use EntretienProfessionnelFormAwareTrait;
    use CampagneFormAwareTrait;
    use ObservationFormAwareTrait;

    use RendererAwareTrait;

    public function indexAction() : ViewModel
    {
        $fromQueries  = $this->params()->fromQuery();
        $agent        = $this->getAgentService()->getAgent($fromQueries['agent']);
        $responsable  = $this->getAgentService()->getAgent($fromQueries['responsable']);
        $structure    = $this->getStructureService()->getStructure($fromQueries['structure']);
        $campagne     = $this->getCampagneService()->getCampagne($fromQueries['campagne']);
        $etat         = $this->getEtatService()->getEtat($fromQueries['etat']);
        $entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnels($agent, $responsable, $structure, $campagne, $etat);

        $campagnes = $this->getCampagneService()->getCampagnes();
        $type = $this->getEtatTypeService()->getEtatTypeByCode('ENTRETIEN_PROFESSIONNEL');
        $etats = $this->getEtatService()->getEtatsByType($type);

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

        $term = $this->params()->fromQuery('term');

        if ($term !== null and trim($term) !== "") {
            $agentsResponsables = $this->getEntretienProfessionnelService()->findResponsablePourEntretien($structure, $term);
            $agentsDelegues = $this->getEntretienProfessionnelService()->findDeleguePourEntretien($structure, $campagne, $term);
            $result = $this->getAgentService()->formatAgentJSON(array_merge($agentsResponsables, $agentsDelegues));
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
            /** @see EntretienProfessionnelController::afficherAction() */
            return $this->redirect()->toRoute('entretien-professionnel/afficher', ["entretien" => $entretien->getId()], [], true);
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
            $element->setAutocompleteSource($this->url()->fromRoute('entretien-professionnel/find-responsable-pour-entretien', ['structure' => $structure->getId(), 'campagne' => $campagne->getId()], [], true));
        }

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
               $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_ACCEPTATION));
               $this->getEntretienProfessionnelService()->initialiser($entretien);
               $this->getNotificationService()->triggerConvocationDemande($entretien);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajout d\'un nouvel entretien professionnel',
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
        $element->setAutocompleteSource($this->url()->fromRoute('entretien-professionnel/find-responsable-pour-entretien', ['structure' => $structure->getId(), 'campagne' => $campagne->getId()], [], true));
        $element = $form->get('agent');
        $element->setAttribute('readonly', true);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_ACCEPTATION));
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

    public function afficherAction() : ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $agent = $this->getAgentService()->getAgent($entretien->getAgent()->getId());
        $mails = $this->getMailService()->getMailsByMotClef($entretien->generateTag());

        $fichesposte = ($agent) ? $this->getFichePosteService()->getFichePosteActiveByAgent($agent) : [];
        $fichesmetiers = [];
        if ($fichesposte) {
            foreach ($fichesposte->getFichesMetiers() as $fiche) {
                $fichesmetiers[] = $fiche->getFicheType();
            }
        }
        $parcours = ($fichesposte) ? $this->getParcoursDeFormationService()->generateParcoursArrayFromFichePoste($fichesposte) : null;

        return new ViewModel([
            'title'                     => 'Entretien professionnel ' . $entretien->getCampagne()->getAnnee() . ' de ' . $entretien->getAgent()->getDenomination(),
            'entretien'                 => $entretien,

            'agent'                     => $agent,
            'fichesposte'               => $fichesposte,
            'fichesmetiers'             => $fichesmetiers,
            'parcours'                  => $parcours,
            'mails'                     => $mails,
            'documents'                 => $this->getEntretienProfessionnelService()->getDocumentsUtiles(),
        ]);
    }

    public function renseignerAction() : ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $agent = $this->getAgentService()->getAgent($entretien->getAgent()->getId());
        $ficheposte = ($agent) ? $this->getFichePosteService()->getFichePosteActiveByAgent($agent) : null;
        $fichesmetiers = [];
        $parcours = ($ficheposte) ? $this->getParcoursDeFormationService()->generateParcoursArrayFromFichePoste($ficheposte) : null;
        $mails = $this->getMailService()->getMailsByMotClef($entretien->generateTag());

        $fiches = ($ficheposte)?$ficheposte->getFichesMetiers():[];
        foreach ($fiches as $fiche) {
            $fichesmetiers[] = $fiche->getFicheType();
        }

        return new ViewModel([
            'entretien' => $entretien,
            'parcours' => $parcours,

            'agent' => $agent,
            'ficheposte' => $this->getFichePosteService()->getFichePosteActiveByAgent($agent),
            'fichesmetiers' => $fichesmetiers,
            'connectedUser' => $this->getUserService()->getConnectedUser(),
            'mails'                     => $mails,
            'documents'                 => $this->getEntretienProfessionnelService()->getDocumentsUtiles(),
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
                        $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_VALIDATION_RESPONSABLE));
                        $this->getEntretienProfessionnelService()->update($entretien);

                        $this->getNotificationService()->triggerValidationResponsableEntretien($entretien);

                        $dateNotification = (new DateTime())->add(new DateInterval('P1W'));
                        $this->getRappelEntretienProfessionnelService()->creer($entretien, $dateNotification);
                        break;

                    case EntretienProfessionnelConstant::VALIDATION_OBSERVATION:
                        $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_VALIDATION_OBSERVATION));
                        $this->getEntretienProfessionnelService()->update($entretien);

                        $this->getNotificationService()->triggerValidationResponsableHierarchique($entretien);
                        if ($entretien->getObservationActive() !== null) $this->getNotificationService()->triggerObservations($entretien);
                        break;

                    case EntretienProfessionnelConstant::VALIDATION_DRH :
                        $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_VALIDATION_HIERARCHIE));
                        $this->getEntretienProfessionnelService()->update($entretien);

                        $this->getNotificationService()->triggerValidationResponsableHierarchique($entretien);
                        break;

                    case EntretienProfessionnelConstant::VALIDATION_AGENT :
                        $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_VALIDATION_AGENT));
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
            $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_ACCEPTER));
        }
        if ($validation->getType()->getCode() === EntretienProfessionnelConstant::VALIDATION_OBSERVATION) {
            $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_VALIDATION_RESPONSABLE));
        }
        if ($validation->getType()->getCode() === EntretienProfessionnelConstant::VALIDATION_DRH) {
            $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_VALIDATION_AGENT));
        }
        if ($validation->getType()->getCode() === EntretienProfessionnelConstant::VALIDATION_AGENT) {
            $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_VALIDATION_HIERARCHIE));
        }

        try {
            $this->getValidationInstanceService()->getEntityManager()->flush($entity);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base.");
        }

        return $this->redirect()->toRoute('entretien-professionnel/renseigner', ['entretien' => $entity->getId()], ['fragment' => 'validation'], true);
    }

    public function exporterAction() : string
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $formations = $this->getAgentService()->getFormationsSuiviesByAnnee($entretien->getAgent(), $entretien->getAnnee());

        $vars= [
            'entretien' => $entretien,
            'agent' => $entretien->getAgent(),
            'formations' => $formations,
            'campagne' => $entretien->getCampagne(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode('ENTRETIEN_PROFESSIONNEL', $vars);

        try {
            $exporter = new PdfExporter();
            $exporter->getMpdf()->SetTitle($rendu->getSujet());
            $exporter->setHeaderScript('');
            $exporter->setFooterScript('');
            $exporter->addBodyHtml($rendu->getCorps());
            return $exporter->export($rendu->getSujet());
        } catch (MpdfException $e) {
            throw new RuntimeException("Un problème est survenue lors de la génértion du PDF", null, $e);
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
            $dateRappel = DateTime::createFromFormat('d/m/Y H:i', $entretien->getDateEntretien()->format('d/m/Y à H:i'))->sub(new DateInterval('P1W'));
            $this->getRappelEntretienProfessionnelService()->creer($entretien, $dateRappel);

            $entretien->setToken(null);
            $entretien->setAcceptation((new DateTime()));
            $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_ACCEPTER));
            $this->getEntretienProfessionnelService()->update($entretien);

            $this->getNotificationService()->triggerConvocationAcceptation($entretien);
        }

        return new ViewModel([
            'entretien' => $entretien,
            'token' => $token,
            'depassee' => $depassee,
        ]);
    }

}
