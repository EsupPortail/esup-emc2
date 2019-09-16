<?php

namespace Application\Controller;

use Application\Entity\Db\FichePoste;
use Application\Form\FichePosteCreation\FichePosteCreationFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\MissionSpecifique\MissionSpecifiqueServiceAwareTrait;
use Application\Service\Poste\PosteServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MesStructuresController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use MissionSpecifiqueServiceAwareTrait;
    use PosteServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    use FichePosteCreationFormAwareTrait;

    public function indexAction()
    {
        $user = $this->getUserService()->getConnectedUser();
        $structures = $this->getStructureService()->getStructuresByGestionnaire($user);


        $structure = $this->getStructureService()->getRequestedStructure($this);
        if ($structure !== null && $structure->getId() === '417') {
            $aya = $this->getUserService()->getUtilisateurByUsername('corbela');
            $dupontc = $this->getUserService()->getUtilisateurByUsername('dupontc01');
            $structure->addGestionnaire($aya);
            $structure->addGestionnaire($dupontc);
        }
        if ($structure === null && count($structures) === 1) {
            return $this->redirect()->toRoute('mes-structures', ['structure' => $structures[0]->getId()], [], true);
        }


        $agentsSansFiche = null;
        $missionsSpecifiques = null;
        $fichesPostes = null;
        if ($structure != null) {
            //TODO sans fiche de poste
            $fichesPostes = $this->getFichePosteService()->getFichesPostesByStructure($structure);
            $agentsSansFiche = $this->getAgentService()->getAgentsSansFichePosteByStructure($structure);
            $missionsSpecifiques = $this->getMissionSpecifiqueService()->getMissionsSpecifiquesByStructure($structure);
        }


        return new ViewModel([
            'user' => $user,
            'structures' => $structures,

            'structure' => $structure,
            'missionsSpecifiques' => $missionsSpecifiques,
            'agentsSansFiche' => $agentsSansFiche,
            'fichesPostes' => $fichesPostes,

        ]);
    }

    public function ajouterFichePosteAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $agent = $this->getAgentService()->getRequestedAgent($this);
        if ($structure === null) {
            throw new RuntimeException("L'ajout de fiche ne peut être fait d'avec une structure donnée !");
        }

        $fiche = new FichePoste();
        if ($agent !== null) {
            $fiche->setAgent($agent);
        }
        $this->getFichePosteService()->create($fiche);
        return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $fiche->getId()], ["query" => ["structure" => $structure->getId()]], true);
    }

    public function dupliquerFichePosteAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $fiches = $this->getFichePosteService()->getFichesPostesByStructure($structure);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $ficheId = $data['fiche'];
            $fiche = $this->getFichePosteService()->getFichePoste($ficheId);

            $nouvelleFiche = new FichePoste();
            $nouvelleFiche->setLibelle($fiche->getLibelle());
            $nouvelleFiche->setAgent($agent);

            //dupliquer specificite
            $specifite = $fiche->getSpecificite()->clone_it();
            $this->getFichePosteService()->createSpecificitePoste($specifite);
            $nouvelleFiche->setSpecificite($specifite);

            $nouvelleFiche = $this->getFichePosteService()->create($nouvelleFiche);

            //dupliquer fiche metier externe
            foreach ($fiche->getFichesMetiers() as $ficheMetierExterne) {
                $nouvelleFicheMetier = $ficheMetierExterne->clone_it();
                $nouvelleFicheMetier->setFichePoste($nouvelleFiche);
                $nouvelleFicheMetier = $this->getFichePosteService()->createFicheTypeExterne($nouvelleFicheMetier);
            }

            /**  Commenter pour eviter perte de temps et clignotement de la modal */
            // return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $nouvelleFiche->getId()], ["query" => ["structure" => $structure->getId()]], true);
        }

        return new ViewModel([
           'title' => "Duplication d'une fiche de poste pour ".$agent->getDenomination()."",
           'structure' => $structure,
           'agent' => $agent,
           'fiches' => $fiches,
        ]);
    }
}