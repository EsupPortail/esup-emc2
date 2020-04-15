<?php

namespace Application\Controller;

use Application\Constant\RoleConstant;
use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentApplication;
use Application\Entity\Db\AgentCompetence;
use Application\Entity\Db\AgentFormation;
use Application\Entity\Db\AgentGrade;
use Application\Entity\Db\AgentMissionSpecifique;
use Application\Entity\Db\AgentStatut;
use Application\Form\Agent\AgentFormAwareTrait;
use Application\Form\AgentApplication\AgentApplicationForm;
use Application\Form\AgentApplication\AgentApplicationFormAwareTrait;
use Application\Form\AgentCompetence\AgentCompetenceFormAwareTrait;
use Application\Form\AgentFormation\AgentFormationFormAwareTrait;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Application\ApplicationServiceAwareTrait;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use Doctrine\ORM\ORMException;
use Fichier\Entity\Db\Fichier;
use Fichier\Form\Upload\UploadFormAwareTrait;
use Fichier\Service\Fichier\FichierServiceAwareTrait;
use Fichier\Service\Nature\NatureServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
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
    use ApplicationServiceAwareTrait;
    use RessourceRhServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;
    use ValidationTypeServiceAwareTrait;
    use NatureServiceAwareTrait;
    use FichierServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    use AgentFormAwareTrait;
    use AgentApplicationFormAwareTrait;
    use AgentCompetenceFormAwareTrait;
    use AgentFormationFormAwareTrait;
    use AgentMissionSpecifiqueFormAwareTrait;
    use UploadFormAwareTrait;


    public function indexAction() {
        $agents = $this->getAgentService()->getAgents();
        return  new ViewModel([
            'agents' => $agents,
        ]);
    }

    public function afficherAction() {

        $agent = $this->getAgentService()->getRequestedAgent($this);
        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();

        return new ViewModel([
            'title' => 'Afficher l\'agent',
            'agent' => $agent,
            'role'  => $role,
            'user'  => $user,
        ]);
    }

    public function afficherStatutsGradesAction() {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $grades = $agent->getGrades();
        usort($grades, function(AgentGrade $a, AgentGrade $b) { return $a->getDateDebut() > $b->getDateDebut();});
        $statuts = $agent->getStatuts();
        usort($statuts, function(AgentStatut $a, AgentStatut $b) { return $a->getDebut() > $b->getDebut();});

        return new ViewModel([
            'title' => 'Listing de tous les status et grades de ' . $agent->getDenomination(),
            'agent' => $agent,
            'statuts' => $statuts,
            'grades' => $grades,
        ]);
    }

    public function modifierAction()
    {
        $agent   = $this->getAgentService()->getRequestedAgent($this);
        $form = $this->getAgentForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/modifier', ['agent' => $agent->getId()], [], true));
        $form->bind($agent);

        /** @var  Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                    $this->getAgentService()->update($agent);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier l\'agent',
            'form' => $form,
        ]);
        return $vm;
    }

    /** Gestion des missions spécifiques ******************************************************************************/

    public function ajouterAgentMissionSpecifiqueAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $agentMissionSpecifique = new AgentMissionSpecifique();
        $agentMissionSpecifique->setAgent($agent);

        /** @var AgentMissionSpecifiqueForm $form */
        $form = $this->getAgentMissionSpecifiqueForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/ajouter-agent-mission-specifique', [ 'agent' => $agent->getId() ], [], true));
        $form->bind($agentMissionSpecifique);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $agentMissionSpecifique->setAgent($agent);
                $this->getAgentService()->createAgentMissionSpecifique($agentMissionSpecifique);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une mission spécifique de l'agent",
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherAgentMissionSpecifiqueAction()
    {
        $agentMissionSpecifique = $this->getAgentService()->getRequestedAgentMissionSpecifique($this);
        return new ViewModel([
            'title' => "Affichage d'une mission spécifique de l'agent",
            'agentMissionSpecifique' => $agentMissionSpecifique,
        ]);
    }

    public function modifierAgentMissionSpecifiqueAction()
    {
        $agentMissionSpecifique = $this->getAgentService()->getRequestedAgentMissionSpecifique($this);
        $agent = $agentMissionSpecifique->getAgent();
        $form = $this->getAgentMissionSpecifiqueForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/modifier-agent-mission-specifique', ['agent-mission-specifique' => $agentMissionSpecifique->getId()]));
        $form->bind($agentMissionSpecifique);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $agentMissionSpecifique->setAgent($agent);
                $this->getAgentService()->updateAgentMissionSpecifique($agentMissionSpecifique);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une mission spécifique de l'agent",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAgentMissionSpecifiqueAction()
    {
        $agentMissionSpecifique = $this->getAgentService()->getRequestedAgentMissionSpecifique($this);
        $this->getAgentService()->historiserAgentMissionSpecifique($agentMissionSpecifique);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $agentMissionSpecifique->getAgent()->getId()], [], true);
    }

    public function restaurerAgentMissionSpecifiqueAction()
    {
        $agentMissionSpecifique = $this->getAgentService()->getRequestedAgentMissionSpecifique($this);
        $this->getAgentService()->restoreAgentMissionSpecifique($agentMissionSpecifique);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $agentMissionSpecifique->getAgent()->getId()], [], true);
    }

    public function detruireAgentMissionSpecifiqueAction()
    {
        $agentMissionSpecifique = $this->getAgentService()->getRequestedAgentMissionSpecifique($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentService()->deleteAgentMissionSpecifique($agentMissionSpecifique);
            //return $this->redirect()->toRoute('home');
            exit();
        }

        $vm = new ViewModel();
        if ($agentMissionSpecifique !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la mission spécifique  de " . $agentMissionSpecifique->getAgent()->getDenomination(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/detruire-agent-mission-specifique', ["agent-mission-specifique" => $agentMissionSpecifique->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Gestion des applications***************************************************************************************/

    public function ajouterAgentApplicationAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $agentApplication = new AgentApplication();

        /** @var AgentApplicationForm $form */
        $form = $this->getAgentApplicationForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/ajouter-agent-application', [ 'agent' => $agent->getId() ], [], true));
        $form->bind($agentApplication);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $agentApplication->setAgent($agent);
                $this->getAgentService()->createAgentApplication($agentApplication);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une application maîtrisée par l'agent",
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherAgentApplicationAction()
    {
        $applicationAgent = $this->getAgentService()->getRequestedAgenApplication($this);
        return new ViewModel([
            'title' => "Affichage d'une application maîtrisée par un agent",
            'applicationAgent' => $applicationAgent,
        ]);
    }

    public function modifierAgentApplicationAction()
    {
        $agenApplication = $this->getAgentService()->getRequestedAgenApplication($this);
        $form = $this->getAgentApplicationForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/modifier-agent-application', ['agent-application' => $agenApplication->getId()]));
        $form->bind($agenApplication);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentService()->updateAgentApplication($agenApplication);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une application maîtrisée par un agent",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAgentApplicationAction()
    {
        $applicationAgent = $this->getAgentService()->getRequestedAgenApplication($this);
        $this->getAgentService()->historiserAgentApplication($applicationAgent);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $applicationAgent->getAgent()->getId()], [], true);
    }

    public function restaurerAgentApplicationAction()
    {
        $applicationAgent = $this->getAgentService()->getRequestedAgenApplication($this);
        $this->getAgentService()->restoreAgentApplication($applicationAgent);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $applicationAgent->getAgent()->getId()], [], true);
    }

    public function detruireAgentApplicationAction()
    {
        $applicationAgent = $this->getAgentService()->getRequestedAgenApplication($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentService()->deleteAgentApplication($applicationAgent);
            //return $this->redirect()->toRoute('home');
            exit();
        }

        $vm = new ViewModel();
        if ($applicationAgent !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'application  de " . $applicationAgent->getAgent()->getDenomination(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/detruire-agent-application', ["agent-application" => $applicationAgent->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Gestion des compétences ***************************************************************************************/

    public function ajouterAgentCompetenceAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $competence = new AgentCompetence();
        $competence->setAgent($agent);
        $form = $this->getAgentCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/ajouter-agent-competence', ['agent' => $agent->getId()]));
        $form->bind($competence);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentService()->createAgentCompetence($competence);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
           'title' => "Ajout d'une compétence associée à un agent",
           'form' => $form,
        ]);
        return $vm;
    }

    public function afficherAgentCompetenceAction()
    {
        $competence = $this->getAgentService()->getRequestedAgentCompetence($this);
        return new ViewModel([
            'title' => "Affichage d'une compétence",
            'competence' => $competence,
        ]);
    }

    public function modifierAgentCompetenceAction()
    {
        $competence = $this->getAgentService()->getRequestedAgentCompetence($this);
        $form = $this->getAgentCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/modifier-agent-competence', ['agent-competence' => $competence->getId()]));
        $form->bind($competence);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentService()->updateAgentCompetence($competence);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une compétence associée à un agent",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAgentCompetenceAction()
    {
        $competence = $this->getAgentService()->getRequestedAgentCompetence($this);
        $this->getAgentService()->historiserAgentCompetence($competence);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $competence->getAgent()->getId()], [], true);
    }

    public function restaurerAgentCompetenceAction()
    {
        $competence = $this->getAgentService()->getRequestedAgentCompetence($this);
        $this->getAgentService()->restoreAgentCompetence($competence);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $competence->getAgent()->getId()], [], true);
    }

    public function detruireAgentCompetenceAction()
    {
        $competence = $this->getAgentService()->getRequestedAgentCompetence($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentService()->deleteAgentCompetence($competence);
            //return $this->redirect()->toRoute('home');
            exit();
        }

        $vm = new ViewModel();
        if ($competence !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la compétence  de " . $competence->getAgent()->getDenomination(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/detruire-agent-competence', ["agent-competence" => $competence->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Gestion des formations ****************************************************************************************/

    public function ajouterAgentFormationAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $agentFormation = new AgentFormation();
        $agentFormation->setAgent($agent);
        $form = $this->getAgentFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/ajouter-agent-formation', ['agent' => $agent->getId()]));
        $form->bind($agentFormation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentService()->createAgentFormation($agentFormation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une formation associée à un agent",
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherAgentFormationAction()
    {
        $agentFormation = $this->getAgentService()->getRequestedAgentFormation($this);
        return new ViewModel([
            'title' => "Affichage d'une formation",
            'competence' => $agentFormation,
        ]);
    }

    public function modifierAgentFormationAction()
    {
        $agentFormation = $this->getAgentService()->getRequestedAgentFormation($this);
        $form = $this->getAgentFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/modifier-agent-formation', ['agent-formation' => $agentFormation->getId()]));
        $form->bind($agentFormation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getAgentService()->updateAgentFormation($agentFormation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une formation associée à un agent",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAgentFormationAction()
    {
        $agentFormation = $this->getAgentService()->getRequestedAgentFormation($this);
        $this->getAgentService()->historiserAgentFormation($agentFormation);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $agentFormation->getAgent()->getId()], [], true);
    }

    public function restaurerAgentFormationAction()
    {
        $agentFormation = $this->getAgentService()->getRequestedAgentFormation($this);
        $this->getAgentService()->restoreAgentFormation($agentFormation);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $agentFormation->getAgent()->getId()], [], true);
    }

    public function detruireAgentFormationAction()
    {
        $agentFormation = $this->getAgentService()->getRequestedAgentFormation($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentService()->deleteAgentFormation($agentFormation);
            //return $this->redirect()->toRoute('home');
            exit();
        }

        $vm = new ViewModel();
        if ($agentFormation !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la formation  de " . $agentFormation->getAgent()->getDenomination(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/detruire-agent-formation', ["agent-formation" => $agentFormation->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Validation élement associée à l'agent *************************************************************************/

    public function validerElementAction()
    {
        $type = $this->params()->fromRoute('type');
        $entityId = $this->params()->fromRoute('id');

        $entity = null;
        $validationType = null;
        $elementText = null;
        switch ($type) {
            case 'AgentApplication' :
                $entity = $this->getAgentService()->getAgentApplication($entityId);
                $validationType = $this->getValidationTypeService()->getValidationTypeByCode("AGENT_APPLICATION");
                $elementText = "l'application [".$entity->getApplication()->getLibelle()."]";
                break;
            case 'AgentCompetence' :
                $entity = $this->getAgentService()->getAgentCompetence($entityId);
                $validationType = $this->getValidationTypeService()->getValidationTypeByCode("AGENT_COMPETENCE");
                $elementText = "la compétence [".$entity->getCompetence()->getLibelle()."]";
                break;
            case 'AgentFormation' :
                $entity = $this->getAgentService()->getAgentFormation($entityId);
                $validationType = $this->getValidationTypeService()->getValidationTypeByCode("AGENT_FORMATION");
                $elementText = "la formation [".$entity->getFormation()->getLibelle()."]";
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

            if ($validation !== null AND $entity !== null) {
                $entity->setValidation($validation);
                switch ($type) {
                    case 'AgentApplication' :
                        $this->getAgentService()->updateAgentApplication($entity);
                        break;
                    case 'AgentCompetence' :
                        $this->getAgentService()->updateAgentCompetence($entity);
                        break;
                    case 'AgentFormation' :
                        $this->getAgentService()->updateAgentFormation($entity);
                        break;
                }
            }
            exit();
        }

        $vm = new ViewModel();
        if ($entity !== null) {
            $vm->setTemplate('unicaen-validation/validation-instance/validation-modal');
            $vm->setVariables([
                'title' => "Validation de ".$elementText,
                'text' => "Validation de ".$elementText,
                'action' => $this->url()->fromRoute('agent/valider-element', ["type" => $type, "id" => $entityId], [], true),
            ]);
        }
        return $vm;
    }

    public function revoquerElementAction()
    {
        $validation = $this->getValidationInstanceService()->getRequestedValidationInstance($this);
        $this->getValidationInstanceService()->historise($validation);

        /** TODO c'est vraiment crado (faire mieux ...) */
        /** @var AgentApplication $entity */
        $entity = $this->getValidationInstanceService()->getEntity($validation);
        $entity->setValidation(null);
        try {
            $this->getValidationInstanceService()->getEntityManager()->flush($entity);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base.");
        }

        return $this->redirect()->toRoute('agent/afficher', ['agent' => $entity->getAgent()->getId()], [], true);
    }
    
    /** Fichier associé à l'agent *************************************************************************************/

    public function uploadFichierAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $fichier = new Fichier();
        $form = $this->getUploadForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/upload-fichier',['agent' => $agent->getId()] , [], true));
        $form->bind($fichier);

        /** !TODO! lorsque l'on est dans une modal on perd le tableau files ... */

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

        $vm =  new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Téléverserment d\'un fichier',
            'form' => $form,
        ]);
        return $vm;
    }

    /** Recherche d'agent  ********************************************************************************************/

    public function rechercherAction()
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getAgentService()->getAgentsByTerm($term);
            $result = $this->formatAgentJSON($agents);
            return new JsonModel($result);
        }
        exit;
    }

    public function rechercherWithStructureMereAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);

        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;

        if (($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getAgentService()->getAgentsByTerm($term, $structures);
            $result = $this->formatAgentJSON($agents);
            return new JsonModel($result);
        }
        exit;
    }

    /**
     * @param Agent[] $agents
     * @return array
     */
    private function formatAgentJSON($agents)
    {
        $result = [];
        /** @var Agent[] $agents */
        foreach ($agents as $agent) {
            $result[] = array(
                'id'    => $agent->getId(),
                'label' => $agent->getDenomination(),
                'extra' => "<span class='badge' style='background-color: slategray;'>".$agent->getSourceName()."</span>",
            );
        }
        usort($result, function($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }

    public function rechercherResponsableAction()
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $responsables = $this->getUserService()->findByTermAndRole($term, RoleConstant::RESPONSABLE_EPRO);
            $result = $result = $this->getUserService()->formatUserJSON($responsables);
            return new JsonModel($result);
        }
        exit;
    }

    public function rechercherGestionnaireAction()
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $gestionnaires = $this->getUserService()->findByTermAndRole($term, RoleConstant::GESTIONNAIRE);
            $result = $this->getUserService()->formatUserJSON($gestionnaires);
            return new JsonModel($result);
        }
        exit;
    }

}