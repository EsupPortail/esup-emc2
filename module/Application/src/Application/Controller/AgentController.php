<?php

namespace Application\Controller;

use Application\Constant\RoleConstant;
use Application\Entity\Db\ApplicationElement;
use Application\Form\ApplicationElement\ApplicationElementFormAwareTrait;
use Application\Form\CompetenceElement\CompetenceElementFormAwareTrait;
use Application\Form\SelectionApplication\SelectionApplicationFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
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

    use FichePosteServiceAwareTrait;

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
        $agent = $this->getAgentService()->getAgent(8486);
//        $agent = $this->getAgentService()->getRequestedAgent($this);
//        $connectedUser = $this->getUserService()->getConnectedUser();
//        $connectedAgent = $this->getAgentService()->getAgentByUser($connectedUser);
//        $connectedRole = $this->getUserService()->getConnectedRole();
//        if ($agent !== $connectedAgent AND ($connectedRole->getRoleId() === RoleConstant::PERSONNEL OR $agent === null)) {
//            return $this->redirect()->toRoute('agent/afficher', ['agent' => $connectedAgent->getId()], [] , true);
//        }
//        $entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnelsParAgent($agent);
//        $responsables = $this->getAgentService()->getResponsablesHierarchiques($agent);
//        $parcoursArray = $this->getParcoursDeFormationService()->generateParcoursArrayFromFichePoste($agent->getFichePosteActif());
//
//        $fichespostes = $this->getFichePosteService()->getFichesPostesByAgent($agent);
//        $missions = $agent->getMissionsSpecifiques();

        return new ViewModel([
            'title' => 'Afficher l\'agent',
            'agent' => $agent,
//            'entretiens' => $entretiens,
//            'responsables' => $responsables,
//            'parcoursArray' => $parcoursArray,
//
//            'fichespostes' => $fichespostes,
//            'missions' => $missions,
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

    /** Gestion des ACQUIS ***************************************************************************************/

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

    /** Validation élement associée à l'agent *************************************************************************/

    public function validerElementAction()
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