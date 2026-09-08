<?php

namespace Carriere\Controller;

use Agent\Service\AgentEmploiType\AgentEmploiTypeServiceAwareTrait;
use Application\Service\Util\UtilServiceAwareTrait;
use Carriere\Provider\Parametre\CarriereParametres;
use Carriere\Service\EmploiType\EmploiTypeServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class EmploiTypeController extends AbstractActionController {
    use AgentEmploiTypeServiceAwareTrait;
    use EmploiTypeServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use UserServiceAwareTrait;
    use UtilServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $agents = $this->getUtilService()->getAgentsSousReponsabilite($user);

        $avecAgent = $this->getParametreService()->getValeurForParametre(CarriereParametres::TYPE, CarriereParametres::CORPS_AVEC_AGENT) === true;
        $emploisTypes = $this->getEmploiTypeService()->getEmploisTypes('libelleLong', 'ASC', false);

        $dictionnaire = $this->getAgentEmploiTypeService()->generateRecensementByEmploiType($agents);

        return new ViewModel([
            "emploisTypes" => $emploisTypes,
            "agents" => $agents,
            "dictionnaire" => $dictionnaire,

            "avecAgent" => $avecAgent,
        ]);
    }

    public function afficherAgentsAction() : ViewModel
    {
        $emploiType = $this->getEmploiTypeService()->getRequestedEmploiType($this);

        $user = $this->getUserService()->getConnectedUser();
        $agents = $this->getUtilService()->getAgentsSousReponsabilite($user);
        $dictionnaire = $this->getAgentEmploiTypeService()->generateDictionnaireWithEmploiType($emploiType, $agents);

        return new ViewModel([
            'title' => "Agents ayant l'emploi-type [" . $emploiType->getLibelleCourt() . "]",
            'emploiType' => $emploiType,
            'agentEmploiTypes' => $dictionnaire,
            'agents' => $agents,
        ]);
    }
}