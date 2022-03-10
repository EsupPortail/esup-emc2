<?php

namespace Application\Controller;

use Application\Constant\RoleConstant;
use Application\Entity\Db\AgentAccompagnement;
use Application\Entity\Db\AgentPPP;
use Application\Entity\Db\AgentStageObservation;
use Application\Entity\Db\AgentTutorat;
use Application\Entity\Db\ApplicationElement;
use Application\Form\AgentAccompagnement\AgentAccompagnementFormAwareTrait;
use Application\Form\AgentPPP\AgentPPPFormAwareTrait;
use Application\Form\AgentStageObservation\AgentStageObservationFormAwareTrait;
use Application\Form\AgentTutorat\AgentTutoratFormAwareTrait;
use Application\Form\ApplicationElement\ApplicationElementFormAwareTrait;
use Application\Form\CompetenceElement\CompetenceElementFormAwareTrait;
use Application\Form\SelectionApplication\SelectionApplicationFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAccompagnement\AgentAccompagnementServiceAwareTrait;
use Application\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Application\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueServiceAwareTrait;
use Application\Service\AgentPPP\AgentPPPServiceAwareTrait;
use Application\Service\AgentQuotite\AgentQuotiteServiceAwareTrait;
use Application\Service\AgentStageObservation\AgentStageObservationServiceAwareTrait;
use Application\Service\AgentStatut\AgentStatutServiceAwareTrait;
use Application\Service\AgentTutorat\AgentTutoratServiceAwareTrait;
use Application\Service\Application\ApplicationServiceAwareTrait;
use Application\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Application\Service\Categorie\CategorieServiceAwareTrait;
use Application\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\HasApplicationCollection\HasApplicationCollectionServiceAwareTrait;
use Application\Service\HasCompetenceCollection\HasCompetenceCollectionServiceAwareTrait;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use Doctrine\ORM\ORMException;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use Fichier\Entity\Db\Fichier;
use Fichier\Form\Upload\UploadFormAwareTrait;
use Fichier\Service\Fichier\FichierServiceAwareTrait;
use Fichier\Service\Nature\NatureServiceAwareTrait;
use Formation\Entity\Db\FormationElement;
use Formation\Form\FormationElement\FormationElementForm;
use Formation\Form\FormationElement\FormationElementFormAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationElement\FormationElementServiceAwareTrait;
use Formation\Service\HasFormationCollection\HasFormationCollectionServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use UnicaenValidation\Entity\Db\ValidationInstance;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;
use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AgentController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use AgentAffectationServiceAwareTrait;
    use AgentGradeServiceAwareTrait;
    use AgentMissionSpecifiqueServiceAwareTrait;
    use AgentQuotiteServiceAwareTrait;
    use AgentStatutServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use UserServiceAwareTrait;

    use ApplicationElementServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use FormationElementServiceAwareTrait;
    use HasApplicationCollectionServiceAwareTrait;
    use HasCompetenceCollectionServiceAwareTrait;
    use HasFormationCollectionServiceAwareTrait;

    use ValidationInstanceServiceAwareTrait;
    use ValidationTypeServiceAwareTrait;
    use NatureServiceAwareTrait;
    use FichierServiceAwareTrait;
    use ApplicationServiceAwareTrait;
    use FormationServiceAwareTrait;
    use CategorieServiceAwareTrait;
    use ParcoursDeFormationServiceAwareTrait;
    use StructureServiceAwareTrait;

    use ApplicationElementFormAwareTrait;
    use CompetenceElementFormAwareTrait;
    use FormationElementFormAwareTrait;
    use SelectionApplicationFormAwareTrait;
    use UploadFormAwareTrait;

    use AgentPPPServiceAwareTrait;
    use AgentPPPFormAwareTrait;
    use AgentStageObservationServiceAwareTrait;
    use AgentStageObservationFormAwareTrait;
    use AgentTutoratServiceAwareTrait;
    use AgentTutoratFormAwareTrait;
    use AgentAccompagnementServiceAwareTrait;
    use AgentAccompagnementFormAwareTrait;

    use EtatTypeServiceAwareTrait;
    use EtatServiceAwareTrait;

    public function indexAction()  : ViewModel
    {
        $agents = $this->getAgentService()->getAgentsPourIndex();
        return new ViewModel([
            'agents' => $agents,
        ]);
    }

    public function afficherAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $utilisateur = $this->getUserService()->getConnectedUser();

        /** si pas d'agent de specifier récupérer l'agent lié au compte de la personne connectée */
        if ($agent === null) {
            if ($utilisateur !== null) $agent = $this->getAgentService()->getAgentByUser($utilisateur);
            if ($agent === null) throw new RuntimeException("Aucun agent n'a pu être trouvé.");
        }

        $agentStatuts = $this->getAgentStatutService()->getAgentStatutsByAgent($agent, true);
        $agentAffectations = $this->getAgentAffectationService()->getAgentAffectationsByAgent($agent, true);
        $agentGrades = $this->getAgentGradeService()->getAgentGradesByAgent($agent, true);

        $connectedAgent = $this->getAgentService()->getAgentByUser($utilisateur);
        $connectedRole = $this->getUserService()->getConnectedRole();
        if ($connectedAgent !== $agent and ($connectedRole->getRoleId() === RoleConstant::PERSONNEL or $agent === null)) {
            return $this->redirect()->toRoute('agent/afficher', ['agent' => $connectedAgent->getId()], [], true);
        }
        $entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnelsByAgent($agent);
        $responsables = $this->getAgentService()->getResponsablesHierarchiques($agent);

        $fichespostes = $this->getFichePosteService()->getFichesPostesByAgents([$agent]);
        $fichePosteActive = $this->getFichePosteService()->getFichePosteActiveByAgent($agent);

        $parcoursArray = $this->getParcoursDeFormationService()->generateParcoursArrayFromFichePoste($fichePosteActive);
//        $applications = $this->getApplicationElementService()->getApplicationElementsByAgent($agent);

        $parametreIntranet = $this->getParametreService()->getParametreByCode('ENTRETIEN_PROFESSIONNEL','INTRANET_DOCUMENT');
        $lienIntranet = ($parametreIntranet)?$parametreIntranet->getValeur():"Aucun lien vers l'intranet";
        
        return new ViewModel([
            'title' => 'Afficher l\'agent',
            'agent' => $agent,
            'affectations' => $agentAffectations,
            'statuts' => $agentStatuts,
            'grades' => $agentGrades,
            'fichespostes' => $fichespostes,
            'ficheposte' => $fichePosteActive,

            'entretiens' => $entretiens,
            'responsables' => $responsables,

            'parcoursArray' => $parcoursArray,

            'quotite' => $this->getAgentQuotiteService()->getAgentQuotiteCurrent($agent),
            'missions' => $this->getAgentMissionSpecifiqueService()->getAgentMissionsSpecifiquesByAgent($agent, false),

            'ppps' => $this->getAgentPPPService()->getAgentPPPsByAgent($agent),
            'stages' =>  $this->getAgentStageObservationService()->getAgentStageObservationsByAgent($agent),
            'tutorats' =>  $this->getAgentTutoratService()->getAgentTutoratsByAgent($agent),
            'accompagnements' => $this->getAgentAccompagnementService()->getAgentAccompagnementsByAgent($agent),
            'intranet' => $lienIntranet,
        ]);
    }

    public function afficherStatutsGradesAction() : ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $agentStatuts = $this->getAgentStatutService()->getAgentStatutsByAgent($agent, false);
        $agentAffectations = $this->getAgentAffectationService()->getAgentAffectationsByAgent($agent, false);
        $agentGrades = $this->getAgentGradeService()->getAgentGradesByAgent($agent, false);

        return new ViewModel([
            'title' => 'Listing de tous les statuts et grades de ' . $agent->getDenomination(),
            'agent' => $agent,
            'affectations' => $agentAffectations,
            'statuts' => $agentStatuts,
            'grades' => $agentGrades,
        ]);
    }

    /** Gestion des ACQUIS ***************************************************************************************/

    public function ajouterFormationAction()  : ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $formation = $this->getFormationService()->getRequestedFormation($this);

        $formationElement = new FormationElement();

        /** @var FormationElementForm $form */
        $form = $this->getFormationElementForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/ajouter-formation', ['agent' => $agent->getId()], [], true));
        $formationElement->setFormation($formation);
        $form->bind($formationElement);


        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getHasFormationCollectionService()->addFormation($agent, $formationElement);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une formation suivie par l'agent",
            'form' => $form,
        ]);
        return $vm;
    }

    /** Validation élement associée à l'agent *************************************************************************/

    public function validerElementAction() : ViewModel
    {
        $type = $this->params()->fromRoute('type');
        $entityId = $this->params()->fromRoute('id');

        $entity = null;
        $validationType = null;
        $elementText = null;
        switch ($type) {
            case 'AGENT_APPLICATION' :
                $entity = $this->getApplicationElementService()->getApplicationElement($entityId);
                $validationType = $this->getValidationTypeService()->getValidationTypeByCode("AGENT_APPLICATION");
                $elementText = "l'application [" . $entity->getApplication()->getLibelle() . "]";
                break;
            case 'AGENT_COMPETENCE' :
                $entity = $this->getCompetenceElementService()->getCompetenceElement($entityId);
                $validationType = $this->getValidationTypeService()->getValidationTypeByCode("AGENT_COMPETENCE");
                $elementText = "la compétence [" . $entity->getCompetence()->getLibelle() . "]";
                break;
            case 'AGENT_FORMATION' :
                $entity = $this->getFormationElementService()->getFormationElement($entityId);
                $validationType = $this->getValidationTypeService()->getValidationTypeByCode("AGENT_FORMATION");
                $elementText = "la formation [" . $entity->getFormation()->getLibelle() . "]";
                break;
        }

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $validation = null;
            if ($data["reponse"] === "oui") {
                $validation = new ValidationInstance();
                $validation->setType($validationType);
                $validation->setEntity($entity);
                $this->getValidationInstanceService()->create($validation);
            }
            if ($data["reponse"] === "non") {
                $validation = new ValidationInstance();
                $validation->setType($validationType);
                $validation->setEntity($entity);
                $validation->setValeur("Refus");
                $this->getValidationInstanceService()->create($validation);
            }

            if ($validation !== null and $entity !== null) {
                $entity->setValidation($validation);
                switch ($type) {
                    case 'AGENT_APPLICATION' :
                        $this->getApplicationElementService()->update($entity);
                        break;
                    case 'AGENT_COMPETENCE' :
                        $this->getCompetenceElementService()->update($entity);
                        break;
                    case 'AGENT_FORMATION' :
                        $this->getFormationElementService()->update($entity);
                        break;
                }
            }
            exit();
        }

        $vm = new ViewModel();
        if ($entity !== null) {
            $vm->setTemplate('unicaen-validation/validation-instance/validation-modal');
            $vm->setVariables([
                'title' => "Validation de " . $elementText,
                'text' => "Validation de " . $elementText,
                'action' => $this->url()->fromRoute('agent/valider-element', ["type" => $type, "id" => $entityId], [], true),
            ]);
        }
        return $vm;
    }

    //TODO fix that ...
    public function revoquerElementAction()
    {
        $retour = $this->params()->fromQuery('retour');

        $validation = $this->getValidationInstanceService()->getRequestedValidationInstance($this);
        $this->getValidationInstanceService()->historise($validation);

        /** @var ApplicationElement $entity */
        $entity = $this->getValidationInstanceService()->getEntity($validation);
        $entity->setValidation(null);
        try {
            $this->getValidationInstanceService()->getEntityManager()->flush($entity);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base.");
        }

        if ($retour !== null) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $entity->getAgent()->getId()], [], true);
    }

    /** Fichier associé à l'agent *************************************************************************************/

    public function uploadFichierAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $fichier = new Fichier();
        $form = $this->getUploadForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/upload-fichier', ['agent' => $agent->getId()], [], true));
        $form->bind($fichier);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $file = current($request->getFiles());

            if ($file['name'] != '') {

                $nature = $this->getNatureService()->getNature($data['nature']);
                $fichier = $this->getFichierService()->createFichierFromUpload($file, $nature);
                $agent->addFichier($fichier);
                $this->getAgentService()->update($agent);
            }
            return $this->redirect()->toRoute('agent/afficher', ['agent' => $agent->getId()]);
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Téléverserment d\'un fichier',
            'form' => $form,
        ]);
        return $vm;
    }

    public function uploadFichePostePdfAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $nature = $this->getNatureService()->getNatureByCode('FICHE_POSTE');

        $fichier = new Fichier();
        $fichier->setNature($nature);
        $form = $this->getUploadForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/upload-fiche-poste-pdf', ['agent' => $agent->getId()], [], true));
        $form->bind($fichier);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $file = current($request->getFiles());

            if ($file['name'] != '') {
                $fichier = $this->getFichierService()->createFichierFromUpload($file, $nature);
                $agent->addFichier($fichier);
                $this->getAgentService()->update($agent);
            }
            return $this->redirect()->toRoute('agent/afficher', ['agent' => $agent->getId()], ['fragment' => 'fiches']);
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Téléverserment d\'un fichier',
            'form' => $form,
        ]);
        return $vm;
    }

    /** Recherche d'agent  ********************************************************************************************/

    public function rechercherAction() : JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getAgentService()->getAgentsByTerm($term);
            $result = $this->getAgentService()->formatAgentJSON($agents);
            return new JsonModel($result);
        }
        exit;
    }

    public function rechercherWithStructureMereAction() : JsonModel
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);

        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;

        if (($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getAgentService()->getAgentsByTerm($term, $structures);
            $result = $this->getAgentService()->formatAgentJSON($agents);
            return new JsonModel($result);
        }
        exit;
    }

    /** PARTIE ASSOCIEE AUX PPP, STAGE, TUTORAT, ACCOMPAGNEMENT *******************************************************/

    public function ajouterPppAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $ppp = new AgentPPP();
        $ppp->setAgent($agent);

        $form = $this->getAgentPPPForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/ppp/ajouter', ['agent' => $agent->getId()], [], true));
        $form->bind($ppp);

        $type = $this->getEtatTypeService()->getEtatTypeByCode('PPP');
        $form->get('etat')->resetEtats($this->getEtatService()->getEtatsByType($type));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentPPPService()->create($ppp);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajouter un projet professionnel personnel",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierPppAction()
    {
        $ppp = $this->getAgentPPPService()->getRequestedAgentPPP($this);

        $form = $this->getAgentPPPForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/ppp/modifier', ['ppp' => $ppp->getId()], [], true));
        $form->bind($ppp);

        $type = $this->getEtatTypeService()->getEtatTypeByCode('PPP');
        $form->get('etat')->resetEtats($this->getEtatService()->getEtatsByType($type));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentPPPService()->update($ppp);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modifier un projet professionnel personnel",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserPppAction()
    {
        $ppp = $this->getAgentPPPService()->getRequestedAgentPPP($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getAgentPPPService()->historise($ppp);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $ppp->getAgent()->getId()], ['fragment' => 'ppp'], true);
    }

    public function restaurerPppAction()
    {
        $ppp = $this->getAgentPPPService()->getRequestedAgentPPP($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getAgentPPPService()->restore($ppp);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $ppp->getAgent()->getId()], ['fragment' => 'ppp'], true);
    }

    public function detruirePppAction()
    {
        $ppp = $this->getAgentPPPService()->getRequestedAgentPPP($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentPPPService()->delete($ppp);
            exit();
        }

        $vm = new ViewModel();
        if ($ppp !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du projet professionnel personnel #" . $ppp->getId(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/ppp/detruire', ["ppp" => $ppp->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** * * * * * * * **/

    public function ajouterStageObservationAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $stageObservation = new AgentStageObservation();
        $stageObservation->setAgent($agent);

        $form = $this->getAgentStageObservationForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/stageobs/ajouter', ['agent' => $agent->getId()], [], true));
        $form->bind($stageObservation);

        $type = $this->getEtatTypeService()->getEtatTypeByCode('STAGE_OBSERVATION');
        $form->get('etat')->resetEtats($this->getEtatService()->getEtatsByType($type));


        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentStageObservationService()->create($stageObservation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajouter un stage d'observation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierStageObservationAction()
    {
        $stageObservation = $this->getAgentStageObservationService()->getRequestedAgentStageObservation($this);

        $form = $this->getAgentStageObservationForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/stageobs/modifier', ['stageobs' => $stageObservation->getId()], [], true));
        $form->bind($stageObservation);

        $type = $this->getEtatTypeService()->getEtatTypeByCode('STAGE_OBSERVATION');
        $form->get('etat')->resetEtats($this->getEtatService()->getEtatsByType($type));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentStageObservationService()->update($stageObservation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modifier un stage d'observation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserStageObservationAction()
    {
        $stageObservation = $this->getAgentStageObservationService()->getRequestedAgentStageObservation($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getAgentStageObservationService()->historise($stageObservation);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $stageObservation->getAgent()->getId()], ['fragment' => 'ppp'], true);
    }

    public function restaurerStageObservationAction()
    {
        $stageObservation = $this->getAgentStageObservationService()->getRequestedAgentStageObservation($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getAgentStageObservationService()->restore($stageObservation);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $stageObservation->getAgent()->getId()], ['fragment' => 'ppp'], true);
    }

    public function detruireStageObservationAction()
    {
        $stageObservation = $this->getAgentStageObservationService()->getRequestedAgentStageObservation($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentStageObservationService()->delete($stageObservation);
            exit();
        }

        $vm = new ViewModel();
        if ($stageObservation !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du stage d'observation #" . $stageObservation->getId(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/stageobs/detruire', ["ppp" => $stageObservation->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** * * * * * * * **/

    public function ajouterTutoratAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $tutorat = new AgentTutorat();
        $tutorat->setAgent($agent);

        $form = $this->getAgentTutoratForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/tutorat/ajouter', ['agent' => $agent->getId()], [], true));
        $form->bind($tutorat);

        $type = $this->getEtatTypeService()->getEtatTypeByCode('TUTORAT');
        $form->get('etat')->resetEtats($this->getEtatService()->getEtatsByType($type));


        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentTutoratService()->create($tutorat);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajouter un tutorat",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierTutoratAction()
    {
        $tutorat = $this->getAgentTutoratService()->getRequestedAgentTutorat($this);

        $form = $this->getAgentTutoratForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/tutorat/modifier', ['tutorat' => $tutorat->getId()], [], true));
        $form->bind($tutorat);

        $type = $this->getEtatTypeService()->getEtatTypeByCode('TUTORAT');
        $form->get('etat')->resetEtats($this->getEtatService()->getEtatsByType($type));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentTutoratService()->update($tutorat);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modifier un tutorat",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserTutoratAction()
    {
        $tutorat = $this->getAgentTutoratService()->getRequestedAgentTutorat($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getAgentTutoratService()->historise($tutorat);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $tutorat->getAgent()->getId()], ['fragment' => 'tutorat'], true);
    }

    public function restaurerTutoratAction()
    {
        $tutorat = $this->getAgentTutoratService()->getRequestedAgentTutorat($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getAgentTutoratService()->restore($tutorat);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $tutorat->getAgent()->getId()], ['fragment' => 'tutorat'], true);
    }

    public function detruireTutoratAction()
    {
        $tutorat = $this->getAgentTutoratService()->getRequestedAgentTutorat($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentTutoratService()->delete($tutorat);
            exit();
        }

        $vm = new ViewModel();
        if ($tutorat !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du tutorat #" . $tutorat->getId(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/tutorat/detruire', ["tutorat" => $tutorat->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** * * * * * * * **/

    public function ajouterAccompagnementAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $accompagnement = new AgentAccompagnement();
        $accompagnement->setAgent($agent);

        $form = $this->getAgentAccompagnementForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/accompagnement/ajouter', ['agent' => $agent->getId()], [], true));
        $form->bind($accompagnement);

        $type = $this->getEtatTypeService()->getEtatTypeByCode('ACCOMPAGNEMENT');
        $form->get('etat')->resetEtats($this->getEtatService()->getEtatsByType($type));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentAccompagnementService()->create($accompagnement);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajouter un accompagnement",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAccompagnementAction()
    {
        $accompagnement = $this->getAgentAccompagnementService()->getRequestedAgentAccompagnement($this);

        $form = $this->getAgentAccompagnementForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/accompagnement/modifier', ['accompagnement' => $accompagnement->getId()], [], true));
        $form->bind($accompagnement);

        $type = $this->getEtatTypeService()->getEtatTypeByCode('ACCOMPAGNEMENT');
        $form->get('etat')->resetEtats($this->getEtatService()->getEtatsByType($type));


        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentAccompagnementService()->update($accompagnement);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modifier un accompagnement",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAccompagnementAction()
    {
        $accompagnement = $this->getAgentAccompagnementService()->getRequestedAgentAccompagnement($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getAgentAccompagnementService()->historise($accompagnement);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $accompagnement->getAgent()->getId()], ['fragment' => 'tutorat'], true);
    }

    public function restaurerAccompagnementAction()
    {
        $accompagnement = $this->getAgentAccompagnementService()->getRequestedAgentAccompagnement($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getAgentAccompagnementService()->restore($accompagnement);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $accompagnement->getAgent()->getId()], ['fragment' => 'tutorat'], true);
    }

    public function detruireAccompagnementAction()
    {
        $accompagnement = $this->getAgentAccompagnementService()->getRequestedAgentAccompagnement($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentAccompagnementService()->delete($accompagnement);
            exit();
        }

        $vm = new ViewModel();
        if ($accompagnement !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'accompagnement #" . $accompagnement->getId(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/accompagnement/detruire', ["accompagnement" => $accompagnement->getId()], [], true),
            ]);
        }
        return $vm;
    }

}
