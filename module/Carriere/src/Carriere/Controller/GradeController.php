<?php

namespace Carriere\Controller;

use Agent\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Application\Service\Util\UtilServiceAwareTrait;
use Carriere\Provider\Parametre\CarriereParametres;
use Carriere\Service\Grade\GradeServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class GradeController extends AbstractActionController {
    use AgentGradeServiceAwareTrait;
    use GradeServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use UserServiceAwareTrait;
    use UtilServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $agents = $this->getUtilService()->getAgentsSousReponsabilite($user);

        $avecAgent = $this->getParametreService()->getValeurForParametre(CarriereParametres::TYPE,CarriereParametres::GRADE_AVEC_AGENT) === true;
        $grades = $this->getGradeService()->getGrades('libelleLong', 'ASC', false);

        $dictionnaire = $this->getAgentGradeService()->generateRecensementByGrade($agents);

        return new ViewModel([
            "grades" => $grades,
            "agents" => $agents,
            "dictionnaire" => $dictionnaire,

            "avecAgent" => $avecAgent,
        ]);
    }

    public function afficherAgentsAction() : ViewModel
    {
        $actifOnly = $this->getParametreService()->getParametreByCode(CarriereParametres::TYPE, CarriereParametres::GRADE_AVEC_AGENT);
        $bool = ($actifOnly) && ($actifOnly->getValeur() === "true");

        $grade = $this->getGradeService()->getRequestedGrade($this);

        $user = $this->getUserService()->getConnectedUser();
        $agents = $this->getUtilService()->getAgentsSousReponsabilite($user);
        $dictionnaire = $this->getAgentGradeService()->generateDictionnaireWithGrade($grade, $agents);

        return new ViewModel([
            'title' => 'Agents ayant le grade [' . $grade->getLibelleCourt() . ']',
            'grade' => $grade,
            'agentGrades' => $dictionnaire,
            'agents' => $agents,
        ]);
    }

    public function rechercherAction() : JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $grades = $this->getGradeService()->getGradesByTerm($term);
            $result = $this->getGradeService()->formatGradesJSON($grades);
            return new JsonModel($result);
        }
        exit;
    }
}