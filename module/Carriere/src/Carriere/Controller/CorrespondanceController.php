<?php

namespace Carriere\Controller;

use Application\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Carriere\Provider\Parametre\CarriereParametres;
use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CorrespondanceController extends AbstractActionController
{
    use AgentGradeServiceAwareTrait;
    use CorrespondanceServiceAwareTrait;
    use ParametreServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $avecAgent = $this->getParametreService()->getParametreByCode(CarriereParametres::TYPE,CarriereParametres::CORRESPONDANCE_AVEC_AGENT);
        $bool = ($avecAgent) && ($avecAgent->getValeur() === "true");
        $correspondances = $this->getCorrespondanceService()->getCorrespondances('libelleLong', 'ASC', $bool);

        return new ViewModel([
            'correspondances' => $correspondances,
        ]);
    }

    public function afficherAgentsAction() : ViewModel
    {
        $actifOnly = $this->getParametreService()->getParametreByCode(CarriereParametres::TYPE,CarriereParametres::ACTIF_ONLY);
        $bool = ($actifOnly) && ($actifOnly->getValeur() === "true");

        $correspondance = $this->getCorrespondanceService()->getRequestedCorrespondance($this);
        $agentGrades = $this->getAgentGradeService()->getAgentGradesByCorrespondance($correspondance, $bool);
        $agents = [];
        foreach ($agentGrades as $agentGrade) {
            $agents[$agentGrade->getAgent()->getId()] = $agentGrade->getAgent();
        }

        return new ViewModel([
            'title' => 'Agents ayant la correspondance ['. $correspondance->getLibelleCourt().']',
            'correspondance' => $correspondance,
            'agents' => $agents,
            'agentGrades' => $agentGrades,
        ]);
    }
}