<?php

namespace Carriere\Controller;

use Application\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Carriere\Provider\Parametre\CarriereParametres;
use Carriere\Service\EmploiType\EmploiTypeServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class EmploiTypeController extends AbstractActionController {
    use AgentGradeServiceAwareTrait;
    use EmploiTypeServiceAwareTrait;
    use ParametreServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $avecAgent = $this->getParametreService()->getParametreByCode(CarriereParametres::TYPE,CarriereParametres::GRADE_AVEC_AGENT);
        $bool = ($avecAgent) && ($avecAgent->getValeur() === "true");
        $emploisTypes = $this->getEmploiTypeService()->getEmploisTypes('libelleLong', 'ASC', $bool);

        return new ViewModel([
            "emploisTypes" => $emploisTypes,
        ]);
    }

    public function afficherAgentsAction() : ViewModel
    {
        $actifOnly = $this->getParametreService()->getParametreByCode(CarriereParametres::TYPE,CarriereParametres::ACTIF_ONLY);
        $bool = ($actifOnly) && ($actifOnly->getValeur() === "true");

        $emploitype = $this->getEmploiTypeService()->getRequestedEmploiType($this);
        $agentGrades = $this->getAgentGradeService()->getAgentGradesByEmploiType($emploitype, $bool);
        $agents = [];
        foreach ($agentGrades as $agentGrade) {
            $agents[$agentGrade->getAgent()->getId()] = $agentGrade->getAgent();
        }

        return new ViewModel([
            'title' => "Agents ayant l'emploi type [". $emploitype->getLibelleCourt()."]",
            'emploiType' => $emploitype,
            'agentGrades' => $agentGrades,
            'agents' => $agents,
        ]);
    }
}