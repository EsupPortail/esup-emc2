<?php

namespace Agent\Controller;

use Agent\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Agent\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Agent\Service\AgentStatut\AgentStatutServiceAwareTrait;
use Agent\Assertion\ChaineAssertion;
use Agent\Entity\Db\AgentAutorite;
use Agent\Entity\Db\AgentSuperieur;
use Agent\Provider\Parametre\AgentParametres;
use Agent\Service\Agent\AgentServiceAwareTrait;
use Agent\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueServiceAwareTrait;
use Agent\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Exception;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use RuntimeException;
use UnicaenFichier\Entity\Db\Fichier;
use UnicaenFichier\Form\Upload\UploadFormAwareTrait;
use UnicaenFichier\Service\Fichier\FichierServiceAwareTrait;
use UnicaenFichier\Service\Nature\NatureServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class AgentController extends AbstractActionController {


    public ChaineAssertion $chaineAssertion;

    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentAffectationServiceAwareTrait;
    use AgentGradeServiceAwareTrait;
    use AgentMissionSpecifiqueServiceAwareTrait;
    use AgentStatutServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use FichierServiceAwareTrait;
    use NatureServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use UserServiceAwareTrait;

    use UploadFormAwareTrait;

    public function acquisAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $applications = $agent->getApplicationDictionnaireComplet();
        $competences = $agent->getCompetenceDictionnaireComplet();

        return new ViewModel([
            'agent' => $agent,
            'applications' => $applications,
            'competences' => $competences,
        ]);
    }

    public function indexAction(): ViewModel|Response
    {
        $params = $this->params()->fromQuery();
        $error = null;
        $agents = [];
        if ($params !== null) {
            if (isset($params['type']) and $params['type'] === 'acceder') {
                $agentId = $params['agent-sas']['id'] ?? null;
                if ($agentId) {
                    /** @see AgentController::informationsAction() */
                    return $this->redirect()->toRoute('agent/informations', ['agent' => $agentId], [], true);
                }
                $agentLabel = $params['agent-sas']['label'] ?? null;

                if ($agentLabel === null or $agentLabel === "") {
                    $agents = [];
                    $error = "Veuillez sélectionner un agent dans la liste des propositions. ";
                } else {
                    $agents = $this->getAgentService()->getAgentsLargeByTerm($agentLabel);
                }
                if (count($agents) === 1) {
                    /** @see AgentController::informationsAction() */
                    return $this->redirect()->toRoute('agent/informations', ['agent' => current($agents)->getId()], [], true);
                }
            }
            if (isset($params['type']) and $params['type'] === 'filtrer') {
                if ($params['denomination'] === "" and $params['structure-filtre']['id'] === "") {
                    $agents = [];
                    $error = "Veuillez préciser la dénomination de l'agent ou une structure";
                } else {
                    $agents = $this->getAgentService()->getAgentsWithFiltre($params);
                }
            }
        }

        return new ViewModel([
            'agents' => $agents,
            'params' => $params,
            'error' => $error,
        ]);
    }

    public function informationsAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        if ($agent === null) $agent = $this->getAgentService()->getAgentByConnectedUser();
        if ($agent === null) throw new RuntimeException("Aucun agent n'a pu être trouvé.");

        //Récupération des status
        $agentAffectations = $this->getAgentAffectationService()->getAgentsAffectationsByAgentAndDate($agent);
        $agentEchelons = $agent->getEchelonsActifs();
        $agentGrades = $this->getAgentGradeService()->getAgentGradesByAgent($agent);
        $agentStatuts = $this->getAgentStatutService()->getAgentStatutsByAgent($agent);

        //Récupération des supérieures et autorités
        $superieurs = array_map(
            function (AgentSuperieur $a) {
                return $a->getSuperieur();
            },
            $this->getAgentSuperieurService()->getAgentsSuperieursByAgent($agent));
        $autorites = array_map(
            function (AgentAutorite $a) {
                return $a->getAutorite();
            },
            $this->getAgentAutoriteService()->getAgentsAutoritesByAgent($agent));


        return new ViewModel([
            'agent' => $agent,
            'autorites' => $autorites,
            'superieurs' => $superieurs,
            'agentAffectations' => $agentAffectations,
            'agentEchelons' => $agentEchelons,
            'agentGrades' => $agentGrades,
            'agentStatuts' => $agentStatuts,

            'chaineAssertion' => $this->chaineAssertion,

            // aide pour la campagne
            'connectedUser' => $this->getUserService()->getConnectedUser(),
            'connectedRole' => $this->getUserService()->getConnectedRole(),
            'campagnesActives' => $this->getCampagneService()->getCampagnesActives(),

        ]);
    }

    public function missionsSpecifiquesAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $missions = $this->getAgentMissionSpecifiqueService()->getAgentMissionsSpecifiquesByAgent($agent, false);

        return new ViewModel([
           'agent' => $agent,
           'missions' => $missions,
        ]);
    }

    public function portfolioAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $fichiers = $agent->getFichiers();

        return new ViewModel([
            'agent' => $agent,
            'fichiers' => $fichiers,
            // onglet
            'parametres' => $this->getParametreService()->getParametresByCategorieCode(AgentParametres::TYPE),
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
        if ($structure === null) {
            throw new RuntimeException("Aucune structure de fournie");
        }

        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;

        if (($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getAgentService()->getAgentsByTerm($term, $structures);
            $result = $this->getAgentService()->formatAgentJSON($agents);
            return new JsonModel($result);
        }
        exit;
    }

    /** depot de fichiers */

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
            $files = $request->getFiles();
            $file = $files['fichier'];

            if ($file['name'] != '') {
                try {
                    $nature = $this->getNatureService()->getNature($data['nature']);
                    $fichier = $this->getFichierService()->createFichierFromUpload($file, $nature);
                } catch (Exception $e) {
                    throw new RuntimeException("Un problème est survenu lors du téléversement", 0, $e);
                }
                $agent->addFichier($fichier);
                $this->getAgentService()->update($agent);
            }

            $retour = $this->params()->fromQuery('retour');
            if ($retour) return $this->redirect()->toUrl($retour);
            exit();
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
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
            /** @see FichePosteController::afficherAgentAction() */
            return $this->redirect()->toRoute('fiche-poste/afficher-agent', ['agent' => $agent->getId()], [], true);
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
}