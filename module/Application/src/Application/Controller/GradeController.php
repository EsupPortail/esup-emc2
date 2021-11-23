<?php

namespace Application\Controller;

use Application\Service\Grade\GradeServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class GradeController extends AbstractActionController {
    use GradeServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $grades = $this->getGradeService()->getGrades();

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