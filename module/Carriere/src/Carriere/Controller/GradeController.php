<?php

namespace Carriere\Controller;

use Carriere\Provider\Parametre\CarriereParametres;
use Carriere\Service\Grade\GradeServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class GradeController extends AbstractActionController {
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
        $grade = $this->getGradeService()->getRequestedGrade($this);

        return new ViewModel([
            'title' => 'Agents ayant le grade ['. $grade->getLibelleCourt().']',
            'grade' => $grade,
        ]);
    }
}