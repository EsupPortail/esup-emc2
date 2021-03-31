<?php

namespace Application\Controller;

use Application\Constant\RoleConstant;
use Application\Entity\Db\ApplicationElement;
use Application\Form\ApplicationElement\ApplicationElementForm;
use Application\Form\ApplicationElement\ApplicationElementFormAwareTrait;
use Application\Form\CompetenceElement\CompetenceElementFormAwareTrait;
use Application\Form\SelectionApplication\SelectionApplicationFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Application\ApplicationServiceAwareTrait;
use Application\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Application\Service\Categorie\CategorieServiceAwareTrait;
use Application\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
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

    use ApplicationElementServiceAwareTrait;
    use HasApplicationCollectionServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use HasCompetenceCollectionServiceAwareTrait;
    use FormationElementServiceAwareTrait;
    use HasFormationCollectionServiceAwareTrait;

    use EntretienProfessionnelServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;
    use ValidationTypeServiceAwareTrait;
    use NatureServiceAwareTrait;
    use FichierServiceAwareTrait;
    use ApplicationServiceAwareTrait;
    use FormationServiceAwareTrait;
    use CategorieServiceAwareTrait;
    use ParcoursDeFormationServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    use ApplicationElementFormAwareTrait;
    use CompetenceElementFormAwareTrait;
    use FormationElementFormAwareTrait;
    use SelectionApplicationFormAwareTrait;
    use UploadFormAwareTrait;

    public function indexAction()
    {
        $fromQueries = $this->params()->fromQuery();
        $filtres = [];
        $clefs = ['titulaire', 'cdi', 'cdd', 'administratif', 'chercheur', 'enseignant', 'vacataire'];
        foreach ($clefs as $clef) {
            if (empty($fromQueries) or $fromQueries[$clef] === 'on') $filtres[$clef] = true;
        }

        $agents = $this->getAgentService()->getAgents($filtres);
        return new ViewModel([
            'agents' => $agents,
            'filtres' => $filtres,
        ]);
    }

    public function afficherAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        if ($agent === null) {
            $agent = $this->getAgentService()->getAgentByUser($this->getUserService()->getConnectedUser());
        }
        $entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnelsParAgent($agent);
        $responsables = $this->getAgentService()->getResponsablesHierarchiques($agent);
        $parcoursArray = $this->getParcoursDeFormationService()->generateParcoursArrayFromFichePoste($agent->getFichePosteActif());

        return new ViewModel([
            'title' => 'Afficher l\'agent',
            'agent' => $agent,
            'entretiens' => $entretiens,
            'responsables' => $responsables,
            'parcoursArray' => $parcoursArray,
        ]);
    }

    public function afficherStatutsGradesAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $affectations = $agent->getAffectations();
        $grades = $agent->getGrades();
        $statuts = $agent->getStatuts();

        return new ViewModel([
            'title' => 'Listing de tous les statuts et grades de ' . $agent->getDenomination(),
            'agent' => $agent,
            'affectations' => $affectations,
            'statuts' => $statuts,
            'grades' => $grades,
        ]);
    }

    /** Gestion des applications***************************************************************************************/

    public function ajouterApplicationAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $application = $this->getApplicationService()->getRequestedApplication($this);
        $applicationElement = new ApplicationElement();

        /** @var ApplicationElementForm $form */
        $form = $this->getApplicationElementForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/ajouter-application', ['agent' => $agent->getId()], [], true));
        $applicationElement->setApplication($application);
        $form->bind($applicationElement);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getHasApplicationCollectionService()->addApplication($agent, $applicationElement);
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

    public function afficherApplicationAction()
    {
        $applicationElement = $this->getApplicationElementService()->getRequestedApplicationElement($this);
        $agent = $this->getAgentService()->getRequestedAgent($this);

        return new ViewModel([
            'title' => "Affichage d'une application maîtrisée par un agent",
            'applicationElement' => $applicationElement,
            'agent' => $agent,
        ]);
    }

    public function modifierApplicationAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $applicationElement = $this->getApplicationElementService()->getRequestedApplicationElement($this);

        $form = $this->getApplicationElementForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/modifier-application', ['agent' => $agent->getId(), 'application-element' => $applicationElement->getId()]));
        $form->bind($applicationElement);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getApplicationElementService()->update($applicationElement);
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

    public function historiserApplicationAction()
    {
        $retour = $this->params()->fromQuery('retour');

        $agent = $this->getAgentService()->getRequestedAgent($this);
        $applicationElement = $this->getApplicationElementService()->getRequestedApplicationElement($this);
        $this->getApplicationElementService()->historise($applicationElement);

        if ($retour !== null) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $agent->getId()], [], true);
    }

    public function restaurerApplicationAction()
    {
        $retour = $this->params()->fromQuery('retour');

        $agent = $this->getAgentService()->getRequestedAgent($this);
        $applicationElement = $this->getApplicationElementService()->getRequestedApplicationElement($this);
        $this->getApplicationElementService()->restore($applicationElement);

        if ($retour !== null) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $agent->getId()], [], true);
    }

    public function detruireApplicationAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $applicationElement = $this->getApplicationElementService()->getRequestedApplicationElement($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            //la cascade doit gérer l'affaire
            if ($data["reponse"] === "oui") $this->getApplicationElementService()->delete($applicationElement);
            exit();
        }

        $vm = new ViewModel();
        if ($applicationElement !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'application  de " . $applicationElement->getApplication()->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/detruire-application', ["agent" => $agent->getId(), "application-element" => $applicationElement->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Gestion des formation ***************************************************************************************/

    public function ajouterFormationAction()
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

    public function afficherFormationAction()
    {
        $formationElement = $this->getFormationElementService()->getRequestedFormationElement($this);
        $agent = $this->getAgentService()->getRequestedAgent($this);

        return new ViewModel([
            'title' => "Affichage d'une formation suivie par un agent",
            'formationElement' => $formationElement,
            'agent' => $agent,
        ]);
    }

    public function modifierFormationAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $formationElement = $this->getFormationElementService()->getRequestedFormationElement($this);

        $form = $this->getFormationElementForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/modifier-formation', ['agent' => $agent->getId(), 'formation-element' => $formationElement->getId()]));
        $form->bind($formationElement);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationElementService()->update($formationElement);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une formation suivie par un agent",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserFormationAction()
    {
        $retour = $this->params()->fromQuery('retour');

        $agent = $this->getAgentService()->getRequestedAgent($this);
        $formationElement = $this->getFormationElementService()->getRequestedFormationElement($this);
        $this->getFormationElementService()->historise($formationElement);

        if ($retour !== null) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $agent->getId()], [], true);
    }

    public function restaurerFormationAction()
    {
        $retour = $this->params()->fromQuery('retour');

        $agent = $this->getAgentService()->getRequestedAgent($this);
        $formationElement = $this->getFormationElementService()->getRequestedFormationElement($this);
        $this->getFormationElementService()->restore($formationElement);

        if ($retour !== null) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $agent->getId()], [], true);
    }

    public function detruireFormationAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $formationElement = $this->getFormationElementService()->getRequestedFormationElement($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            //la cascade doit gérer l'affaire
            if ($data["reponse"] === "oui") $this->getFormationElementService()->delete($formationElement);
            exit();
        }

        $vm = new ViewModel();
        if ($formationElement !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la formation de " . $formationElement->getFormation()->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/detruire-formation', ["agent" => $agent->getId(), "formation-element" => $formationElement->getId()], [], true),
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
                $entity = $this->getApplicationElementService()->getApplicationElement($entityId);
                $validationType = $this->getValidationTypeService()->getValidationTypeByCode("AGENT_APPLICATION");
                $elementText = "l'application [" . $entity->getApplication()->getLibelle() . "]";
                break;
            case 'AgentCompetence' :
                $entity = $this->getCompetenceElementService()->getCompetenceElement($entityId);
                $validationType = $this->getValidationTypeService()->getValidationTypeByCode("AGENT_COMPETENCE");
                $elementText = "la compétence [" . $entity->getCompetence()->getLibelle() . "]";
                break;
            case 'AgentFormation' :
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
                    case 'AgentApplication' :
                        $this->getApplicationElementService()->update($entity);
                        break;
                    case 'AgentCompetence' :
                        $this->getCompetenceElementService()->update($entity);
                        break;
                    case 'AgentFormation' :
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

    //TODO non retour retour semble merder

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

    /** Recherche d'agent  ********************************************************************************************/

    public function rechercherAction()
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getAgentService()->getAgentsByTerm($term);
            $result = $this->getAgentService()->formatAgentJSON($agents);
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
            $result = $this->getAgentService()->formatAgentJSON($agents);
            return new JsonModel($result);
        }
        exit;
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
            $gestionnaires = $this->getUserService()->findByTerm($term);
            $result = $this->getUserService()->formatUserJSON($gestionnaires);
            return new JsonModel($result);
        }
        exit;
    }

}