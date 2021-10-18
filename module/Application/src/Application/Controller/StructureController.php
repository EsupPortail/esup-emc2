<?php

namespace Application\Controller;

use Application\Constant\RoleConstant;
use Application\Entity\Db\FichePoste;
use Application\Entity\Db\Structure;
use Application\Entity\Db\StructureAgentForce;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueFormAwareTrait;
use Application\Form\AjouterGestionnaire\AjouterGestionnaireForm;
use Application\Form\AjouterGestionnaire\AjouterGestionnaireFormAwareTrait;
use Application\Form\HasDescription\HasDescriptionFormAwareTrait;
use Application\Form\SelectionAgent\SelectionAgentFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\FicheProfil\FicheProfilServiceAwareTrait;
use Application\Service\MissionSpecifique\MissionSpecifiqueAffectationServiceAwareTrait;
use Application\Service\Poste\PosteServiceAwareTrait;
use Application\Service\SpecificitePoste\SpecificitePosteServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use Application\Service\StructureAgentForce\StructureAgentForceServiceAwareTrait;
use DateTime;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenApp\View\Model\CsvModel;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class StructureController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use FicheProfilServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use MissionSpecifiqueAffectationServiceAwareTrait;
    use PosteServiceAwareTrait;
    use RoleServiceAwareTrait;
    use StructureServiceAwareTrait;
    use StructureAgentForceServiceAwareTrait;
    use UserServiceAwareTrait;
    use SpecificitePosteServiceAwareTrait;

    use EntretienProfessionnelServiceAwareTrait;
    use CampagneServiceAwareTrait;

    use AgentMissionSpecifiqueFormAwareTrait;
    use AjouterGestionnaireFormAwareTrait;
    use SelectionAgentFormAwareTrait;
    use HasDescriptionFormAwareTrait;

    public function indexAction()
    {
        $structures = $this->getStructureService()->getStructures();
        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();

        return new ViewModel([
            'structures' => $structures,
            'user' => $user,
            'role' => $role,
        ]);
    }

    public function afficherAction()
    {
        /** Préparation du selecteur quand le rôle le demande **/
        $role = $this->getUserService()->getConnectedRole();
        $selecteur = [];
        if ($role->getRoleId() === RoleConstant::GESTIONNAIRE) {
            $user = $this->getUserService()->getConnectedUser();
            $structures = $this->getStructureService()->getStructuresByGestionnaire($user);
            usort($structures, function(Structure $a, Structure $b) {return $a->getLibelleCourt() > $b->getLibelleCourt();});
            $selecteur = $structures;
        }
        if ($role->getRoleId() === RoleConstant::RESPONSABLE) {
            $user = $this->getUserService()->getConnectedUser();
            $structures = $this->getStructureService()->getStructuresByResponsable($user);
            usort($structures, function(Structure $a, Structure $b) {return $a->getLibelleCourt() > $b->getLibelleCourt();});
            $selecteur = $structures;
        }
        if ($role->getRoleId() === RoleConstant::ADMIN_TECH OR $role->getRoleId() === RoleConstant::ADMIN_FONC OR $role->getRoleId() === RoleConstant::OBSERVATEUR) {
            $unicaen = $this->getStructureService()->getStructure(1);
            $structures = $this->getStructureService()->getSousStructures($unicaen, true);
            usort($structures, function(Structure $a, Structure $b) {return $a->getLibelleCourt() > $b->getLibelleCourt();});
            $selecteur = array_filter($structures, function (Structure $s) {return ($s->getHisto() === null);});
        }

        /** Récupération des structures */
        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');
        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] =  $structure;

        /** Récupération des missions spécifiques liées aux structures */
        $missionsSpecifiques = $this->getMissionSpecifiqueAffectationService()->getMissionsSpecifiquesByStructures($structures);

        /** Récupération des fiches de postes liées aux structures */
        $fichesPostes = $this->getFichePosteService()->getFichesPostesByStructures($structures, true);
        $fichesCompletes = []; $fichesIncompletes = [];
        foreach ($fichesPostes as $fichePoste) {
            if ($fichePoste->isComplete()) {
                $fichesCompletes[] = $fichePoste;
            } else {
                $fichesIncompletes[] = $fichePoste;
            }
        }
        $fichesRecrutements = $this->getStructureService()->getFichesPostesRecrutementsByStructures($structures);
        /** Récupération des agents et postes liés aux structures */
        $agents = $this->getAgentService()->getAgentsByStructures($structures);
        $agentsForces = array_map(function (StructureAgentForce $a) { return $a->getAgent(); }, $structure->getAgentsForces());

        $postes = $this->getPosteService()->getPostesByStructures($structures);

        /** Campagne */
        $last = $this->getCampagneService()->getLastCampagne();
        $campagnes = $this->getCampagneService()->getCampagnesActives();
        $entretiens = [];

        $inscriptions = $this->getFormationInstanceInscritService()->getInscriptionsByStructure($structure);
        $profils = $this->getFicheProfilService()->getFichesPostesByStructure($structure);

        return new ViewModel([
            'selecteur' => $selecteur,

            'structure' => $structure,
            'filles' =>   $structure->getEnfants(),

            'missions' => $missionsSpecifiques,
            'fichesCompletes' => $fichesCompletes,
            'fichesIncompletes' => $fichesIncompletes,
            'fichesRecrutements' => $fichesRecrutements,
            'profils' => $profils,
            'inscriptions' => $inscriptions,
            'agents' => $agents,
            'agentsForces' => $agentsForces,
            'postes' => $postes,

            'last' => $last,
            'campagnes' => $campagnes,
            'entretiens' => $entretiens,
        ]);
    }

    /** Action associé à la partie résumé : description et gestionnaire  **********************************************/

    public function editerDescriptionAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');

        $form = $this->getHasDescriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('structure/editer-description', ['structure' => $structure->getId()], [] , true));
        $form->bind($structure);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getStructureService()->update($structure);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Édition de la description de la structure',
            'form' => $form,
        ]);
        return $vm;
    }

    /** GESTION DES RESPONSABLES ET GESTIONNAIRES *******************************************************************/

    public function ajouterGestionnaireAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');
        /** @var AjouterGestionnaireForm $form */
        $form = $this->getAjouterGestionnaireForm();
        $form->setAttribute('action', $this->url()->fromRoute('structure/ajouter-gestionnaire', ['structure' => $structure->getId()]));
        /** @var SearchAndSelect $element */
        $element = $form->get('structure');
        /** @see StructureController::rechercherWithStructureMereAction() */
        $element->setAutocompleteSource($this->url()->fromRoute('structure/rechercher-with-structure-mere', ['structure' => $structure->getId()], [], true));

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $gestionnaire = $this->getUserService()->getUtilisateur($data['gestionnaire']['id']);
            $structure = $this->getStructureService()->getStructure($data['structure']['id']);
            if ($gestionnaire !== null AND $structure !== null) {
                if (!$gestionnaire->hasRole(RoleConstant::GESTIONNAIRE)) {
                    $gestionnaireRole = $this->getRoleService()->getRoleByCode(RoleConstant::GESTIONNAIRE);
                    if (!$gestionnaire->hasRole($gestionnaireRole)) $gestionnaire->addRole($gestionnaireRole);
                    $this->getUserService()->update($gestionnaire);
                }
                $structure->addGestionnaire($gestionnaire);
                $this->getStructureService()->update($structure);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate("application/default/default-form");
        $vm->setVariables([
            'title' => "Ajout d'un gestionnaire à une structure",
            'form' => $form,
        ]);
        return $vm;
    }

    public function retirerGestionnaireAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');
        $gestionnaire = $this->getUserService()->getUtilisateur($this->params()->fromRoute('gestionnaire'));

        $structure->removeGestionnaire($gestionnaire);
        $this->getStructureService()->update($structure);

        $structures = $this->getStructureService()->getStructuresByGestionnaire($gestionnaire);
        if (empty($structures)) {
            $role = $this->getRoleService()->getRoleByCode(RoleConstant::GESTIONNAIRE);
            $this->getUserService()->removeRole($gestionnaire, $role);
        }

        return $this->redirect()->toRoute('structure/afficher', ['structure' => $structure->getId()], [], true);
    }

//    public function ajouterResponsableAction()
//    {
//        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');
//        /** @var AjouterGestionnaireForm $form */
//        $form = $this->getAjouterGestionnaireForm();
//        $form->setAttribute('action', $this->url()->fromRoute('structure/ajouter-responsable', ['structure' => $structure->getId()]));
//        /** @var SearchAndSelect $element */
//        $element = $form->get('structure');
//        /** @see StructureController::rechercherWithStructureMereAction() */
//        $element->setAutocompleteSource($this->url()->fromRoute('structure/rechercher-with-structure-mere', ['structure' => $structure->getId()], [], true));
//
//        /** @var Request $request */
//        $request = $this->getRequest();
//        if ($request->isPost()) {
//            $data = $request->getPost();
//            $responsable = $this->getUserService()->getUtilisateur($data['gestionnaire']['id']);
//            $structure = $this->getStructureService()->getStructure($data['structure']['id']);
//            if ($responsable !== null AND $structure !== null) {
//                if (!$responsable->hasRole(RoleConstant::RESPONSABLE)) {
//                    $responsableRole = $this->getRoleService()->getRoleByCode(RoleConstant::RESPONSABLE);
//                    if (!$responsable->hasRole($responsableRole)) $responsable->addRole($responsableRole);
//                    $this->getUserService()->update($responsable);
//                }
//                $structure->addResponsable($responsable);
//                $this->getStructureService()->update($structure);
//            }
//        }
//
//        $form->get('gestionnaire')->setLabel('Responsable * :');
//        $vm = new ViewModel();
//        $vm->setTemplate("application/default/default-form");
//        $vm->setVariables([
//            'title' => "Ajout d'un&middot;e responsable à une structure",
//            'form' => $form,
//        ]);
//        return $vm;
//    }
//
//    public function retirerResponsableAction()
//    {
//        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');
//        $responsable = $this->getUserService()->getUtilisateur($this->params()->fromRoute('responsable'));
//
//        $structure->removeResponsable($responsable);
//        $this->getStructureService()->update($structure);
//
//        $structures = $this->getStructureService()->getStructuresByResponsable($responsable);
//        if (empty($structures)) {
//            $role = $this->getRoleService()->getRoleByCode(RoleConstant::RESPONSABLE);
//            $this->getUserService()->removeRole($responsable, $role);
//        }
//
//        return $this->redirect()->toRoute('structure/afficher', ['structure' => $structure->getId()], [], true);
//    }

    /** RESUME *********************************************************************************************************/

    public function toggleResumeMereAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');
        $structure->setRepriseResumeMere(! $structure->getRepriseResumeMere());
        $this->getStructureService()->update($structure);

        return $this->redirect()->toRoute('structure/afficher', ['structure' => $structure->getId()], [], true);
    }

    /** Fonctions de recherche ****************************************************************************************/

    public function rechercherAction()
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $structures = $this->getStructureService()->getStructuresByTerm($term);
            $result = $this->getStructureService()->formatStructureJSON($structures);
            return new JsonModel($result);
        }
        exit;
    }

    public function rechercherWithStructureMereAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);

        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;

        $term = $this->params()->fromQuery('term');
        if ($term) {
            $structures = $this->getStructureService()->getStructuresByTerm($term, $structures);
            $result = $this->getStructureService()->formatStructureJSON($structures);
            return new JsonModel($result);
        }
        exit;
    }

    /**
     * @return JsonModel
     */
    public function rechercherGestionnairesAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);

        $users = $this->getStructureService()->getGestionnairesByStructure($structure);

        $selected = [];

        $term = $this->params()->fromQuery('term');
        if ($term) {
            $term = strtolower($term);
            foreach ($users as $user) {
                if (strpos(strtolower($user->getDisplayName()), $term) !== false) $selected[] = $user;
            }
            $result = $this->getUserService()->formatUserJSON($selected);
            return new JsonModel($result);
        }
        exit;
    }

    /** AGENTS FORCES *************************************************************************************************/

    public function ajouterManuellementAgentAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $structureAgentForce = new StructureAgentForce();
        $structureAgentForce->setStructure($structure);

        $form = $this->getSelectionAgentForm();
        $form->setAttribute('action',$this->url()->fromRoute('structure/ajouter-manuellement-agent', ['structure' => $structure->getId()], [], true));
        $form->bind($structureAgentForce);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                if ($this->getStructureAgentForceService()->getStructureAgentForceByStructureAndAgent($structureAgentForce->getStructure(), $structureAgentForce->getAgent()) === null) {
                    $this->getStructureAgentForceService()->create($structureAgentForce);
                }
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Forcer manuellement l'ajout d'un agent",
            'form' => $form,
        ]);
        return $vm;
    }

    public function retirerManuellementAgentAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $structureAgentForce = $this->getStructureAgentForceService()->getStructureAgentForceByStructureAndAgent($structure, $agent);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getStructureAgentForceService()->delete($structureAgentForce);
            exit();
        }

        $vm = new ViewModel();
        if ($structureAgentForce !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Retirer [".$structureAgentForce->getAgent()->getDenomination()."] de la structure [" . $structureAgentForce->getStructure()->getLibelleCourt() . "]",
                'text' => "Le retrait est définitif, êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('structure/retirer-manuellement-agent', ["structure" => $structure->getId(), "agent" => $agent->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** FiCHE DE POSTE RECRUTEMENT ************************************************************************************/

    public function ajouterFichePosteRecrutementAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $fiche = new FichePoste();
        $this->getFichePosteService()->create($fiche);

        $structure->addFichePosteRecrutement($fiche);
        $this->getStructureService()->update($structure);

        /** @see FichePosteController::editerAction() */
        return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $fiche->getId()], ["query" => ["structure" => $structure->getId()]], true);
    }

    public function dupliquerFichePosteRecrutementAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $fiche = $this->getFichePosteService()->getFichePoste($data['fiche']);

            if ($fiche != null) {
                $nouvelleFiche = new FichePoste();
                $nouvelleFiche->setLibelle($fiche->getLibelle());
                if ($fiche->getSpecificite()) {
                    $specifite = $fiche->getSpecificite()->clone_it();
                    $this->getSpecificitePosteService()->create($specifite);
                    $nouvelleFiche->setSpecificite($specifite);
                }
                $nouvelleFiche = $this->getFichePosteService()->create($nouvelleFiche);

                //dupliquer fiche metier externe
                foreach ($fiche->getFichesMetiers() as $ficheMetierExterne) {
                    $nouvelleFicheMetier = $ficheMetierExterne->clone_it();
                    $nouvelleFicheMetier->setFichePoste($nouvelleFiche);
                    $this->getFichePosteService()->createFicheTypeExterne($nouvelleFicheMetier);
                }

                $structure->addFichePosteRecrutement($nouvelleFiche);
                $this->getStructureService()->update($structure);
            }


        }

        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;
        $fichesPostesAgent = $this->getFichePosteService()->getFichesPostesByStructures($structures);
        $fichesPostesRecrutement = $structure->getFichesPostesRecrutements();

        $fichespostes = [];
        foreach ($fichesPostesAgent as $fichePoste) $fichespostes[$fichePoste->getId()] = $fichePoste;
        foreach ($fichesPostesRecrutement as $fichePoste) $fichespostes[$fichePoste->getId()] = $fichePoste;

        $vm =  new ViewModel([
            'title' => 'Sélectionner la fiche de poste à dupliquer',
            'fichespostes' => $fichespostes,
            'url' => $this->url()->fromRoute('structure/dupliquer-fiche-poste-recrutement', ['structure' => $structure->getId()], [], true),
        ]);
        $vm->setTemplate('application/structure/dupliquer-fiche-poste');
        return $vm;
    }

    /** EXTRACTIONS ***************************************************************************************************/

    /**
     * Extraction du listing des agents et des fiches de poste associées
     */
    public function extractionListingFichePosteAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;
        $agents = $this->getAgentService()->getAgentsByStructures($structures);

        $filename = "listing_fiche_poste_-_" . str_replace(" ","_",$structure->getLibelleCourt()) . "_-_" . (new DateTime())->format('ymd-hms') . ".csv";
        $header = ["Agent", "Fiche metier principale", "Complement"];

        $result = [];
        foreach ($agents as $agent) {
            $denomination = $agent->getDenomination();
            $fiche = "";
            $complement = "";

            $ficheposte = $agent->getFichePosteActif();
            if ($ficheposte !== null) {
                $fiche = $ficheposte->getLibelleMetierPrincipal(FichePoste::TYPE_GENRE);
                $complement = $ficheposte->getLibelle();
            }

            $result[] = [$denomination, $fiche, $complement];
        }

        $CSV = new CsvModel();
        $CSV->setDelimiter(';');
        $CSV->setEnclosure('"');
        $CSV->setHeader($header);
        $CSV->setData($result);
        $CSV->setFilename($filename);
        return $CSV;

    }

    /**
     * Extraction du listing des agents et des fiches de poste associées
     */
    public function extractionListingMissionSpecifiqueAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;
        $missionsSpecifiques = $this->getMissionSpecifiqueAffectationService()->getMissionsSpecifiquesByStructures($structures);


        $filename = "listing_mission_specifique-_" . str_replace(" ","_",$structure->getLibelleCourt()) . "_-_" . (new DateTime())->format('ymd-hms') . ".csv";
        $header = ["Agent", "Structure", "Mission", "Début de mission", "Fin de mission", "Volume"];

        $result = [];
        foreach ($missionsSpecifiques as $mission) {
            $agent = $mission->getAgent()->getDenomination();
            $structure = $mission->getStructure()->getLibelleLong();
            $libelle = $mission->getMission()->getLibelle();
            $debut = ($mission->getDateDebut())?$mission->getDateDebut()->format('d/m/Y'):"";
            $fin = ($mission->getDateFin())?$mission->getDateFin()->format('d/m/Y'):"";
            $volume = $mission->getDecharge();
            $result[] = [$agent, $structure, $libelle, $debut, $fin, $volume];
        }

        $CSV = new CsvModel();
        $CSV->setDelimiter(';');
        $CSV->setEnclosure('"');
        $CSV->setHeader($header);
        $CSV->setData($result);
        $CSV->setFilename($filename);
        return $CSV;
    }

}