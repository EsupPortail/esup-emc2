<?php

namespace Application\Controller;

use Application\Service\Corps\CorpsServiceAwareTrait;
use Application\Service\Correspondance\CorrespondanceServiceAwareTrait;
use Application\Service\Grade\GradeServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CorpsController extends AbstractActionController {
    use CorpsServiceAwareTrait;
    use CorrespondanceServiceAwareTrait;
    use GradeServiceAwareTrait;

    public function indexAction()
    {
        $corps = $this->getCorpsService()->getCorps();
        $correspondances = $this->getCorrespondanceService()->getCorrespondances();
        $grades = $this->getGradeService()->getGrades();

        return new ViewModel([
            "corps" => $corps,
            "correspondances" => $correspondances,
            "grades" => $grades,
        ]);
    }


}