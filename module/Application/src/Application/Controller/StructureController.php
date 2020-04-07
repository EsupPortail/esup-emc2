<?php

namespace Application\Controller;

use Application\Entity\Db\AgentMissionSpecifique;
use Application\Entity\Db\Structure;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueFormAwareTrait;
use Application\Form\AjouterGestionnaire\AjouterGestionnaireFormAwareTrait;
use Application\Form\Structure\StructureFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\MissionSpecifique\MissionSpecifiqueServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use Fhaculty\Graph\Graph;
use Graphp\GraphViz\GraphViz;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class StructureController extends AbstractActionController {
    use RoleServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;
    use AgentServiceAwareTrait;
    use MissionSpecifiqueServiceAwareTrait;

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
        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');
        $structuresFilles = $this->getStructureService()->getStructuresFilles($structure);

        $structures = $structuresFilles;
        $structures[] =  $structure;

        $missionsSpecifiques = $this->getMissionSpecifiqueService()->getMissionsSpecifiquesByStructures($structures);

        return new ViewModel([
            'structure' => $structure,
            'filles' =>  $structuresFilles,
            'missions' => $missionsSpecifiques,
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

    public function ajouterGestionnaireAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');
        $form = $this->getAjouterGestionnaireForm();
        $form->setAttribute('action', $this->url()->fromRoute('structure/ajouter-gestionnaire', ['structure' => $structure->getId()]));
        /** @see StructureController::rechercherWithStructureMereAction() */
        $form->get('structure')->setAutocompleteSource($this->url()->fromRoute('structure/rechercher-with-structure-mere', ['structure' => $structure->getId()], [], true));

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $gestionnaire = $this->getUserService()->getUtilisateur($data['gestionnaire']['id']);
            $structure = $this->getStructureService()->getStructure($data['structure']['id']);
            if ($gestionnaire !== null AND $structure !== null) {
                $this->getStructureService()->addGestionnaire($structure, $gestionnaire);
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

        $this->getStructureService()->removeGestionnaire($structure, $gestionnaire);
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

    /** Actions associées aux affectations de missions spécifiques *****************************************************
     * -> pas d'affichage car tout est dans le tableau ?
    *******************************************************************************************************************/

    public function ajouterAffectationAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);

        $affectation = new AgentMissionSpecifique();
        $form = $this->getAgentMissionSpecifiqueForm();
        $form->setAttribute('action', $this->url()->fromRoute('structure/ajouter-affectation', ['structure' => $structure->getId()]));
        /** @see AgentController::rechercherWithStructureMereAction() */
        $form->get('agent')->setAutocompleteSource($this->url()->fromRoute('agent/rechercher-with-structure-mere', ['structure' => $structure->getId()], [], true));
        /** @see StructureController::rechercherWithStructureMereAction() */
        $form->get('structure')->setAutocompleteSource($this->url()->fromRoute('structure/rechercher-with-structure-mere', ['structure' => $structure->getId()], [], true));
        $form->bind($affectation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionSpecifiqueService()->create($affectation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une affectation de mission spécifique",
            'form'  => $form,
        ]);
        return $vm;
    }

    public function modifierAffectationAction()
    {
        $affectation = $this->getMissionSpecifiqueService()->getRequestedAffectation($this);

        $form = $this->getAgentMissionSpecifiqueForm();
        $form->setAttribute('action', $this->url()->fromRoute('structure/modifier-affectation', ['affectation' => $affectation->getId()]));
        $form->bind($affectation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionSpecifiqueService()->update($affectation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une affectation de mission spécifique",
            'form'  => $form,
        ]);
        return $vm;
    }

    public function historiserAffectationAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $affectation = $this->getMissionSpecifiqueService()->getRequestedAffectation($this);

        $this->getMissionSpecifiqueService()->historise($affectation);

        return $this->redirect()->toRoute('structure/afficher', ['structure' => $structure->getId()], [], true);
    }

    public function restaurerAffectationAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $affectation = $this->getMissionSpecifiqueService()->getRequestedAffectation($this);

        $this->getMissionSpecifiqueService()->restore($affectation);

        return $this->redirect()->toRoute('structure/afficher', ['structure' => $structure->getId()], [], true);

    }

    public function detruireAffectationAction()
    {
        $affectation = $this->getMissionSpecifiqueService()->getRequestedAffectation($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getMissionSpecifiqueService()->delete($affectation);
            exit();
        }

        $vm = new ViewModel();
        if ($affectation !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'affectation de " . $affectation->getAgent()->getDenomination(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('structure/detruire-affectation', ["affectation" => $affectation->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** AUTRE FONCTIONS */

    public function grapheAction() {
        $graph = new Graph();
        $structureMere = $this->getStructureService()->getRequestedStructure($this);

        $structures = [];
        if ($structureMere === null) {
            $structures = $this->getStructureService()->getStructures(true);
        } else {
            $file = [];
            $file[] = $structureMere;
            while (!empty($file)) {
                /** @var Structure $structure */
                $structure = array_shift($file);

                $structures[] = $structure;
                $enfants = $structure->getEnfants()->toArray();
                /** @var Structure $enfant */
                foreach ($enfants as $enfant) {
                    if ($enfant->getHisto() === 'O' ) {
                        $file[] = $enfant;
                    }
                }
            }
        }
        $array = [];
        foreach ($structures as $structure) {
            $agentsTexte = "";
            $agents = $this->getAgentService()->getAgentsByStructure($structure);
            foreach ($agents as $agent) {
                $agentsTexte .= "- " . $agent->getDenomination() . "\n";
            }
            $header = $structure->getId()." - ".$structure->getLibelleCourt();
            $vertex = $graph->createVertex($header);
            $vertex->setAttribute('graphviz.shape', 'plaintext');
            $text  = "<table>";
            $text .= "<tr><td>".$structure->getId()."</td><td>".$structure->getLibelleCourt()."</td></tr>";
            $text .= "<tr><td colspan='2' style='text-align: left'>";
            foreach ($agents as $agent) $text .= $agent->getDenomination() . "<br/>";
            $text .= "</td></tr>";
            $text .= "</table>";
            $vertex->setAttribute('graphviz.label', GraphViz::raw("<".$text.">"));

//            $raw = GraphViz::raw("<table><tr><td>Hello</td></tr></table>"); //"<table><tr><td>".$structure->getId()."</td><td>" . $structure->getLibelleCourt() . "</td></tr></table>"); // . $agentsTexte);
//            $vertex->setAttribute("graphviz.label", $raw);
            switch ($structure->getType()) {
                case "Établissement" :
//                    $vertex->setAttribute('graphviz.shape', 'none');
                    break;
                case "Composante" :
//                    $vertex->setAttribute('graphviz.shape', 'none');
//                    $vertex->setAttribute('graphviz.color', 'blue');
//                    $vertex->setAttribute('graphviz.fontcolor', 'blue');
                    break;
                case "Bibliothèque" :
//                    $vertex->setAttribute('graphviz.shape', 'none');
//                    $vertex->setAttribute('graphviz.color', 'Hotpink');
//                    $vertex->setAttribute('graphviz.fontcolor', 'Hotpink');
                    break;
                case "Antenne" :
                case "Département" :
//                    $vertex->setAttribute('graphviz.shape', 'none');
//                    $vertex->setAttribute('graphviz.color', 'blue');
//                    $vertex->setAttribute('graphviz.style', 'dashed');
//                    $vertex->setAttribute('graphviz.fontcolor', 'blue');
                    break;
                case "Structure de recherche" :
//                    $vertex->setAttribute('graphviz.shape', 'none');
//                    $vertex->setAttribute('graphviz.style', 'rounded');
//                    $vertex->setAttribute('graphviz.color', 'green');
//                    $vertex->setAttribute('graphviz.fontcolor', 'green');
                    break;
                case "Service central" :
//                    $vertex->setAttribute('graphviz.shape', 'none');
//                    $vertex->setAttribute('graphviz.color', 'red');
//                    $vertex->setAttribute('graphviz.fontcolor', 'red');
                    break;
                case "Service commun" :
//                    $vertex->setAttribute('graphviz.shape', 'none');
//                    $vertex->setAttribute('graphviz.color', 'darkorange');
//                    $vertex->setAttribute('graphviz.fontcolor', 'darkorange');
                    break;
                case "Sous-structure administrative" :
//                    $vertex->setAttribute('graphviz.shape', 'none');
//                    $vertex->setAttribute('graphviz.style', 'dashed');
                    $typeParent = null;
                    $parent = $structure->getParent();
                    while ($typeParent === null AND $parent !== null) {
                        if ($parent->getType() !== "Sous-structure administrative") $typeParent = $parent->getType();
                        $parent = $parent->getParent();
                    }
                    if ($typeParent === "Service central") {
//                        $vertex->setAttribute('graphviz.color', 'red');
//                        $vertex->setAttribute('graphviz.fontcolor', 'red');
                    }
                    if ($typeParent === "Service commun") {
//                        $vertex->setAttribute('graphviz.color', 'darkorange');
//                        $vertex->setAttribute('graphviz.fontcolor', 'darkorange');
                    }
                    if ($typeParent === "Composante" OR $typeParent === "Antenne" OR $typeParent === "Département" ) {
//                        $vertex->setAttribute('graphviz.color', 'blue');
//                        $vertex->setAttribute('graphviz.fontcolor', 'blue');
                    }
                    break;
            }
            $array[$structure->getId()] = $vertex;

        }
        foreach ($structures as $structure) {
            $son = $array[$structure->getId()];
            $father = null;
            if ($structure->getParent()) $father = $array[$structure->getParent()->getId()];

            if ($father && $son) $father->createEdgeTo($son);
        }

        $viz = new GraphViz();
//        $img = $viz->createImageData($graph);
        $img = $viz->createImageHtml($graph);
        return new ViewModel([
            'img' => $img,
        ]);
    }
}