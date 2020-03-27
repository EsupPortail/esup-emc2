<?php

namespace Application\Controller;

use Application\Entity\Db\Structure;
use Application\Form\Structure\StructureFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use Fhaculty\Graph\Graph;
use Graphp\GraphViz\GraphViz;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StructureController extends AbstractActionController {
    use RoleServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;
    use AgentServiceAwareTrait;

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

        return new ViewModel([
            'structure' => $structure,
        ]);
    }

    public function ajouterGestionnaireAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');
        if ($structure) {

            /** @var Request $request */
            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                $gestionnaire = $this->getUserService()->getUtilisateur($data['gestionnaire']);
                if ($gestionnaire) {
                    $this->getStructureService()->addGestionnaire($structure, $gestionnaire);
                }
            } else {
                /** @var User[] $gestionnaires */
                $roleGestionnaire = $this->getRoleService()->getRoleByCode(Role::GESTIONNAIRE);
                $gestionnaires = array_diff($this->getUserService()->getUtilisateursByRole($roleGestionnaire),$structure->getGestionnaires());
                usort($gestionnaires, function(User $a, User $b) {return $a->getDisplayName()>$b->getDisplayName();});
                return new ViewModel([
                    'title' => 'Ajout d \'un gestionnaire', // pour ['.$composante->getLibelle().']',
                    'structure' => $structure,
                    'gestionnaires' => $gestionnaires,
                ]);
            }

        } else {
            throw new RuntimeException("Aucune structure de remontée !");
        }

        exit;
    }

    public function retirerGestionnaireAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');

        if ($structure) {
            /** @var Request $request */
            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                $gestionnaire = $this->getUserService()->getUtilisateur($data['gestionnaire']);
                if ($gestionnaire) {
                    $this->getStructureService()->removeGestionnaire($structure, $gestionnaire);
                }
            } else {
                /** @var User[] $gestionnaires */
                $gestionnaires = $structure->getGestionnaires();
                usort($gestionnaires, function(User $a, User $b) {return $a->getDisplayName()>$b->getDisplayName();});
                return new ViewModel([
                    'title' => 'Retrait d \'un gestionnaire', // pour ['.$composante->getLibelle().']',
                    'structure' => $structure,
                    'gestionnaires' => $gestionnaires,
                ]);
            }

        } else {
            throw new RuntimeException("Aucune composante de remontée !");
        }

        exit;
    }

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