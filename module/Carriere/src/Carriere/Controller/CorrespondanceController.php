<?php

namespace Carriere\Controller;

use Application\Entity\Db\AgentGrade;
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

        $agentGrades = [];
        foreach ($correspondances as $correspondance) {
            $agentGrades[$correspondance->getId()] = $this->getAgentGradeService()->getAgentGradesByCorrespondance($correspondance);
        }
        return new ViewModel([
            'correspondances' => $correspondances,
            'agentGrades' => $agentGrades,
        ]);
    }

    public function afficherAgentsAction() : ViewModel
    {
        $actifOnly = true; //todo recup param

        $correspondance = $this->getCorrespondanceService()->getRequestedCorrespondance($this);
        /** @var AgentGrade[] $agentGrades */
        $agentGrades = $this->getAgentGradeService()->getAgentGradesByCorrespondance($correspondance, $actifOnly);
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