<?php

namespace Carriere\Controller;

use Application\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Carriere\Provider\Parametre\CarriereParametres;
use Carriere\Service\Grade\GradeServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class GradeController extends AbstractActionController {
    use AgentGradeServiceAwareTrait;
    use GradeServiceAwareTrait;
    use ParametreServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $avecAgent = $this->getParametreService()->getParametreByCode(CarriereParametres::TYPE,CarriereParametres::GRADE_AVEC_AGENT);
        $bool = ($avecAgent) && ($avecAgent->getValeur() === "true");
        $grades = $this->getGradeService()->getGrades('libelleLong', 'ASC', $bool);

        return new ViewModel([
            'grades' => $grades,
        ]);
    }

    public function afficherAgentsAction() : ViewModel
    {
        $actifOnly = $this->getParametreService()->getParametreByCode(CarriereParametres::TYPE,CarriereParametres::ACTIF_ONLY);
        $bool = ($actifOnly) && ($actifOnly->getValeur() === "true");

        $grade = $this->getGradeService()->getRequestedGrade($this);
        $agentGrades = $this->getAgentGradeService()->getAgentGradesByGrade($grade, $bool);
        $agents = [];
        foreach ($agentGrades as $agentGrade) {
            $agents[$agentGrade->getAgent()->getId()] = $agentGrade->getAgent();
        }

        return new ViewModel([
            'title' => 'Agents ayant le grade ['. $grade->getLibelleCourt().']',
            'grade' => $grade,
            'agentGrades' => $agentGrades,
            'agents' => $agents,
        ]);
    }
}