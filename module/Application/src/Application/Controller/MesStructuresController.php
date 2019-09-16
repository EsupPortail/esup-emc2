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
        if ($structure === null) {
            throw new RuntimeException("L'ajout de fiche ne peut être fait d'avec une structure donnée !");
        }

        $fiche = new FichePoste();
        $fiche->setLibelle("Fiche de poste sans titre");
        $this->getFichePosteService()->create($fiche);
        return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $fiche->getId()], ["query" => ["structure" => $structure->getId()]], true);
    }
}