<?php

namespace Application\Controller;

use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Form\AgentAccompagnement\AgentAccompagnementFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAccompagnement\AgentAccompagnementServiceAwareTrait;
use Application\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueServiceAwareTrait;
use Application\Service\AgentMobilite\AgentMobiliteServiceAwareTrait;
use Application\Service\AgentPPP\AgentPPPServiceAwareTrait;
use Application\Service\AgentQuotite\AgentQuotiteServiceAwareTrait;
use Application\Service\AgentStageObservation\AgentStageObservationServiceAwareTrait;
use Application\Service\AgentStatut\AgentStatutServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Application\Service\AgentTutorat\AgentTutoratServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceAwareTrait;
use Carriere\Service\Categorie\CategorieServiceAwareTrait;
use DateTime;
use Doctrine\ORM\Exception\ORMException;
use Element\Form\ApplicationElement\ApplicationElementFormAwareTrait;
use Element\Form\CompetenceElement\CompetenceElementFormAwareTrait;
use Element\Form\SelectionApplication\SelectionApplicationFormAwareTrait;
use Element\Service\Application\ApplicationServiceAwareTrait;
use Element\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Element\Service\HasApplicationCollection\HasApplicationCollectionServiceAwareTrait;
use Element\Service\HasCompetenceCollection\HasCompetenceCollectionServiceAwareTrait;
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
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use UnicaenValidation\Entity\Db\ValidationInstance;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;
use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;

class AgentController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
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
    use AgentStageObservationServiceAwareTrait;
    use AgentTutoratServiceAwareTrait;
    use AgentAccompagnementServiceAwareTrait;
    use AgentAccompagnementFormAwareTrait;

    use AgentMobiliteServiceAwareTrait;


    public function indexAction(): ViewModel
    {
        $params = $this->params()->fromQuery();
        $agents = [];
        if ($params !== null and !empty($params)) {
            $agents = $this->getAgentService()->getAgentsWithFiltre($params);
        }

        return new ViewModel([
            'agents' => $agents,
            'params' => $params,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        //Recupération de l'agent
        $agent = $this->getAgentService()->getRequestedAgent($this);
        if ($agent === null) $agent = $this->getAgentService()->getAgentByConnectedUser();
        if ($agent === null) throw new RuntimeException("Aucun agent n'a pu être trouvé.");

        //Récupération des status
        $agentStatuts = $this->getAgentStatutService()->getAgentStatutsByAgent($agent);
        $agentAffectations = $this->getAgentAffectationService()->getAgentsAffectationsByAgentAndDate($agent, new DateTime());
        $agentGrades = $this->getAgentGradeService()->getAgentGradesByAgent($agent);

        //Récupération des supérieures et autorités
        $superieures = array_map(
            function (AgentSuperieur $a) {
                return $a->getSuperieur();
            },
            $this->getAgentSuperieurService()->getAgentsSuperieursByAgent($agent));
        $autorites = array_map(
            function (AgentAutorite $a) {
                return $a->getAutorite();
            },
            $this->getAgentAutoriteService()->getAgentsAutoritesByAgent($agent));

        $fichespostes = $this->getFichePosteService()->getFichesPostesByAgent($agent);

        $parametreIntranet = $this->getParametreService()->getParametreByCode('ENTRETIEN_PROFESSIONNEL', 'INTRANET_DOCUMENT');
        $lienIntranet = ($parametreIntranet) ? $parametreIntranet->getValeur() : "Aucun lien vers l'intranet";

        $mobilites = $this->getAgentMobiliteService()->getAgentsMobilitesByAgent($agent);

        return new ViewModel([
            'title' => 'Afficher l\'agent',
            'agent' => $agent,
            'affectations' => $agentAffectations,
            'statuts' => $agentStatuts,
            'grades' => $agentGrades,
            'echelons' => $agent->getEchelonsActifs(),
            'fichespostes' => $fichespostes,

            'superieures' => $superieures,
            'autorites' => $autorites,

//          'parcoursArray' => $parcoursArray,

            'quotite' => $this->getAgentQuotiteService()->getAgentQuotiteCurrent($agent),
            'missions' => $this->getAgentMissionSpecifiqueService()->getAgentMissionsSpecifiquesByAgent($agent, false),

            'ppps' => $this->getAgentPPPService()->getAgentPPPsByAgent($agent),
            'stages' => $this->getAgentStageObservationService()->getAgentStageObservationsByAgent($agent),
            'tutorats' => $this->getAgentTutoratService()->getAgentTutoratsByAgent($agent),
            'accompagnements' => $this->getAgentAccompagnementService()->getAgentAccompagnementsByAgent($agent),
            'intranet' => $lienIntranet,

            'mobilites' => $mobilites,
        ]);
    }

    public function afficherStatutsGradesAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $agentStatuts = $this->getAgentStatutService()->getAgentStatutsByAgent($agent, false);
        $agentAffectations = $this->getAgentAffectationService()->getAgentAffectationsByAgent($agent, false);
        $agentGrades = $this->getAgentGradeService()->getAgentGradesByAgent($agent, false);

        $param = $this->getParametreService()->getParametreByCode('GLOBAL', 'CODE_UNIV');
        $codeEtabPrincipal = ($param) ? $param->getValeur() : null;

        return new ViewModel([
            'title' => 'Listing de tous les statuts et grades de ' . $agent->getDenomination(),
            'agent' => $agent,
            'affectations' => $agentAffectations,
            'statuts' => $agentStatuts,
            'grades' => $agentGrades,
            'codeEtabPrincipal' => $codeEtabPrincipal,
        ]);
    }

    /** Gestion des ACQUIS ***************************************************************************************/

    public function ajouterFormationAction(): ViewModel
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

    public function validerElementAction(): ViewModel
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
                $this->getValidationInstanceService()->create($validation);
            }
            if ($data["reponse"] === "non") {
                $validation = new ValidationInstance();
                $validation->setType($validationType);
                $validation->setRefus(true);
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

    /** TODO :: reprendre le même systeme que pour la validation pour ne plus utiliser la méthode getEntity **/
    public function revoquerElementAction(): Response
    {
        $validation = $this->getValidationInstanceService()->getRequestedValidationInstance($this);
        $this->getValidationInstanceService()->historise($validation);

        $type = $this->params()->fromRoute('type');
        $entityId = $this->params()->fromRoute('id');
        switch ($type) {
            case 'AGENT_APPLICATION' :
                $entity = $this->getApplicationElementService()->getApplicationElement($entityId);
                break;
            case 'AGENT_COMPETENCE' :
                $entity = $this->getCompetenceElementService()->getCompetenceElement($entityId);
                break;
            case 'AGENT_FORMATION' :
                $entity = $this->getFormationElementService()->getFormationElement($entityId);
                break;
        }

        $entity->setValidation(null);
        try {
            $this->getValidationInstanceService()->getEntityManager()->flush($entity);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base.", 0, $e);
        }

        $retour = $this->params()->fromQuery('retour');
        if ($retour !== null) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('home', [], [], true);
    }

    /** Fichier associé à l'agent *************************************************************************************/

    public function uploadFichierAction(): ViewModel|Response
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

    public function uploadFichePostePdfAction(): ViewModel|Response
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
            $file = $request->getFiles()['fichier'];

            if ($file['name'] != '') {
                $fichier = $this->getFichierService()->createFichierFromUpload($file, $nature);
                $agent->addFichier($fichier);
                $this->getAgentService()->update($agent);
            }
            return $this->redirect()->toRoute('agent/afficher', ['agent' => $agent->getId()], ['fragment' => 'fiches']);
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Téléverserment d\'un fichier',
            'form' => $form,
            'warning' => "<span class='icon icon-attention'></span> Attention la taille de la fiche de poste ne doit pas dépaser 2 Mo."
        ]);
        return $vm;
    }

    /** Vérification lien Utilisateur <=> Agent **/

    public function verifierLienAction(): ViewModel
    {
        $user = $this->getUserService()->getRequestedUser($this);
        if ($user === null) $user = $this->getUserService()->getConnectedUser();

        $agentByUser = $this->getAgentService()->getAgentByUser($user);
        $agentByLogin = $this->getAgentService()->getAgentByLogin($user->getUsername());
        if ($agentByUser === null and $agentByLogin === null) {
            throw new RuntimeException(
                "
                Aucun agent de trouvé depuis l'utilisateur·trice connecté·e
                [id:" . $user->getId() . " username:" . $user->getUsername() . "] 
            ", 0);
        }

        return new ViewModel([
            'utilisateur' => $user,
            'agentByUser' => $agentByUser,
            'agentByLogin' => $agentByLogin,
        ]);
    }

    /** Recherche d'agent  ********************************************************************************************/

    public function rechercherLargeAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getAgentService()->getAgentsLargeByTerm($term);
            $result = $this->getAgentService()->formatAgentJSON($agents);
            return new JsonModel($result);
        }
        exit;
    }

    public function rechercherAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getAgentService()->getAgentsByTerm($term);
            $result = $this->getAgentService()->formatAgentJSON($agents);
            return new JsonModel($result);
        }
        exit;
    }

    public function rechercherWithStructureMereAction(): JsonModel
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

}
