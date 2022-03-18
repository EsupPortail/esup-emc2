<?php

namespace Carriere\Controller;

use Carriere\Service\Grade\GradeServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class GradeController extends AbstractActionController {
    use GradeServiceAwareTrait;
    use ParametreServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $avecAgent = $this->getParametreService()->getParametreByCode('carriere','GradeAvecAgent');
        $bool = ($avecAgent) && ($avecAgent->getValeur() === true);
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