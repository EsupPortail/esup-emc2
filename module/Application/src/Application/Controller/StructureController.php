<?php

namespace Application\Controller;

use Application\Constant\RoleConstant;
use Application\Entity\Db\Structure;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueFormAwareTrait;
use Application\Form\AjouterGestionnaire\AjouterGestionnaireForm;
use Application\Form\AjouterGestionnaire\AjouterGestionnaireFormAwareTrait;
use Application\Form\Structure\StructureFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelCampagneServiceAwareTrait;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\MissionSpecifique\MissionSpecifiqueAffectationServiceAwareTrait;
use Application\Service\Poste\PosteServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class StructureController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use MissionSpecifiqueAffectationServiceAwareTrait;
    use PosteServiceAwareTrait;
    use RoleServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    use EntretienProfessionnelServiceAwareTrait;
    use EntretienProfessionnelCampagneServiceAwareTrait;

    use AgentMissionSpecifiqueFormAwareTrait;
    use AjouterGestionnaireFormAwareTrait;
    use StructureFormAwareTrait;

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
        /** Récupération des agents et postes liés aux structures */
        $agents = $this->getAgentService()->getAgentsByStructures($structures);
        $postes = $this->getPosteService()->getPostesByStructures($structures);

        /** Campagne */
        $last = $this->getEntretienProfessionnelCampagneService()->getLastCampagne();
        $campagnes = $this->getEntretienProfessionnelCampagneService()->getCampagnesActives();
        $entretiens = [];

        return new ViewModel([
            'selecteur' => $selecteur,

            'structure' => $structure,
            'filles' =>   $structure->getEnfants(),

            'missions' => $missionsSpecifiques,
            'fichesCompletes' => $fichesCompletes,
            'fichesIncompletes' => $fichesIncompletes,
            'agents' => $agents,
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

        $form = $this->getStructureForm();
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

        return $this->redirect()->toRoute('structure/afficher', ['structure' => $structure->getId()], [], true);
    }

    public function ajouterResponsableAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');
        /** @var AjouterGestionnaireForm $form */
        $form = $this->getAjouterGestionnaireForm();
        $form->setAttribute('action', $this->url()->fromRoute('structure/ajouter-responsable', ['structure' => $structure->getId()]));
        /** @var SearchAndSelect $element */
        $element = $form->get('structure');
        /** @see StructureController::rechercherWithStructureMereAction() */
        $element->setAutocompleteSource($this->url()->fromRoute('structure/rechercher-with-structure-mere', ['structure' => $structure->getId()], [], true));

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $responsable = $this->getUserService()->getUtilisateur($data['gestionnaire']['id']);
            $structure = $this->getStructureService()->getStructure($data['structure']['id']);
            if ($responsable !== null AND $structure !== null) {
                if (!$responsable->hasRole(RoleConstant::RESPONSABLE)) {
                    $responsableRole = $this->getRoleService()->getRoleByCode(RoleConstant::RESPONSABLE);
                    if (!$responsable->hasRole($responsableRole)) $responsable->addRole($responsableRole);
                    $this->getUserService()->update($responsable);
                }
                $structure->addResponsable($responsable);
                $this->getStructureService()->update($structure);
            }
        }

        $form->get('gestionnaire')->setLabel('Responsable * :');
        $vm = new ViewModel();
        $vm->setTemplate("application/default/default-form");
        $vm->setVariables([
            'title' => "Ajout d'un&middot;e responsable à une structure",
            'form' => $form,
        ]);
        return $vm;
    }

    public function retirerResponsableAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');
        $responsable = $this->getUserService()->getUtilisateur($this->params()->fromRoute('responsable'));

        $structure->removeResponsable($responsable);
        $this->getStructureService()->update($structure);

        return $this->redirect()->toRoute('structure/afficher', ['structure' => $structure->getId()], [], true);
    }

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
            $result = $result = $this->formatStructureJSON($structures);
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
            $result = $this->formatStructureJSON($structures);
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
            $result = $this->formatUtilisateurJSON($selected);
            return new JsonModel($result);
        }
        exit;
    }

    /**
     * @param Structure[] $structures
     * @return array
     */
    private function formatStructureJSON($structures)
    {
        $result = [];
        foreach ($structures as $structure) {
            $result[] = array(
                'id'    => $structure->getId(),
                'label' => $structure->getLibelleLong(),
                'extra' => "<span class='badge' style='background-color: slategray;'>".$structure->getLibelleCourt()."</span>",
            );
        }
        usort($result, function($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }

    /**
     * @param User[] $users
     * @return array
     */
    private function formatUtilisateurJSON($users)
    {
        $result = [];
        foreach ($users as $user) {
            $result[] = array(
                'id'    => $user->getId(),
                'label' => $user->getDisplayName(),
                'extra' => "<span class='badge' style='background-color: slategray;'>".$user->getEmail()."</span>",
            );
        }
        usort($result, function($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }

    /** AUTRE FONCTIONS */

//    public function grapheAction() {
//        $graph = new Graph();
//        $structureMere = $this->getStructureService()->getRequestedStructure($this);
//
//        $structures = [];
//        if ($structureMere === null) {
//            $structures = $this->getStructureService()->getStructures(true);
//        } else {
//            $file = [];
//            $file[] = $structureMere;
//            while (!empty($file)) {
//                /** @var Structure $structure */
//                $structure = array_shift($file);
//
//                $structures[] = $structure;
//                $enfants = $structure->getEnfants()->toArray();
//                /** @var Structure $enfant */
//                foreach ($enfants as $enfant) {
//                    if ($enfant->getHisto() === 'O' ) {
//                        $file[] = $enfant;
//                    }
//                }
//            }
//        }
//        $array = [];
//        foreach ($structures as $structure) {
//            $agentsTexte = "";
//            $agents = $this->getAgentService()->getAgentsByStructure($structure);
//            foreach ($agents as $agent) {
//                $agentsTexte .= "- " . $agent->getDenomination() . "\n";
//            }
//            $header = $structure->getId()." - ".$structure->getLibelleCourt();
//            $vertex = $graph->createVertex($header);
//            $vertex->setAttribute('graphviz.shape', 'plaintext');
//            $text  = "<table>";
//            $text .= "<tr><td>".$structure->getId()."</td><td>".$structure->getLibelleCourt()."</td></tr>";
//            $text .= "<tr><td colspan='2' style='text-align: left'>";
//            foreach ($agents as $agent) $text .= $agent->getDenomination() . "<br/>";
//            $text .= "</td></tr>";
//            $text .= "</table>";
//            $vertex->setAttribute('graphviz.label', GraphViz::raw("<".$text.">"));
//
////            $raw = GraphViz::raw("<table><tr><td>Hello</td></tr></table>"); //"<table><tr><td>".$structure->getId()."</td><td>" . $structure->getLibelleCourt() . "</td></tr></table>"); // . $agentsTexte);
////            $vertex->setAttribute("graphviz.label", $raw);
//            switch ($structure->getType()) {
//                case "Établissement" :
////                    $vertex->setAttribute('graphviz.shape', 'none');
//                    break;
//                case "Composante" :
////                    $vertex->setAttribute('graphviz.shape', 'none');
////                    $vertex->setAttribute('graphviz.color', 'blue');
////                    $vertex->setAttribute('graphviz.fontcolor', 'blue');
//                    break;
//                case "Bibliothèque" :
////                    $vertex->setAttribute('graphviz.shape', 'none');
////                    $vertex->setAttribute('graphviz.color', 'Hotpink');
////                    $vertex->setAttribute('graphviz.fontcolor', 'Hotpink');
//                    break;
//                case "Antenne" :
//                case "Département" :
////                    $vertex->setAttribute('graphviz.shape', 'none');
////                    $vertex->setAttribute('graphviz.color', 'blue');
////                    $vertex->setAttribute('graphviz.style', 'dashed');
////                    $vertex->setAttribute('graphviz.fontcolor', 'blue');
//                    break;
//                case "Structure de recherche" :
////                    $vertex->setAttribute('graphviz.shape', 'none');
////                    $vertex->setAttribute('graphviz.style', 'rounded');
////                    $vertex->setAttribute('graphviz.color', 'green');
////                    $vertex->setAttribute('graphviz.fontcolor', 'green');
//                    break;
//                case "Service central" :
////                    $vertex->setAttribute('graphviz.shape', 'none');
////                    $vertex->setAttribute('graphviz.color', 'red');
////                    $vertex->setAttribute('graphviz.fontcolor', 'red');
//                    break;
//                case "Service commun" :
////                    $vertex->setAttribute('graphviz.shape', 'none');
////                    $vertex->setAttribute('graphviz.color', 'darkorange');
////                    $vertex->setAttribute('graphviz.fontcolor', 'darkorange');
//                    break;
//                case "Sous-structure administrative" :
////                    $vertex->setAttribute('graphviz.shape', 'none');
////                    $vertex->setAttribute('graphviz.style', 'dashed');
//                    $typeParent = null;
//                    $parent = $structure->getParent();
//                    while ($typeParent === null AND $parent !== null) {
//                        if ($parent->getType() !== "Sous-structure administrative") $typeParent = $parent->getType();
//                        $parent = $parent->getParent();
//                    }
//                    if ($typeParent === "Service central") {
////                        $vertex->setAttribute('graphviz.color', 'red');
////                        $vertex->setAttribute('graphviz.fontcolor', 'red');
//                    }
//                    if ($typeParent === "Service commun") {
////                        $vertex->setAttribute('graphviz.color', 'darkorange');
////                        $vertex->setAttribute('graphviz.fontcolor', 'darkorange');
//                    }
//                    if ($typeParent === "Composante" OR $typeParent === "Antenne" OR $typeParent === "Département" ) {
////                        $vertex->setAttribute('graphviz.color', 'blue');
////                        $vertex->setAttribute('graphviz.fontcolor', 'blue');
//                    }
//                    break;
//            }
//            $array[$structure->getId()] = $vertex;
//
//        }
//        foreach ($structures as $structure) {
//            $son = $array[$structure->getId()];
//            $father = null;
//            if ($structure->getParent()) $father = $array[$structure->getParent()->getId()];
//
//            if ($father && $son) $father->createEdgeTo($son);
//        }
//
//        $viz = new GraphViz();
////        $img = $viz->createImageData($graph);
//        $img = $viz->createImageHtml($graph);
//        return new ViewModel([
//            'img' => $img,
//        ]);
//    }
}