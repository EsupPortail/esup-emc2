<?php

namespace Application\Controller;

use Application\Service\Categorie\CategorieServiceAwareTrait;
use Application\Service\Corps\CorpsServiceAwareTrait;
use Application\Service\Correspondance\CorrespondanceServiceAwareTrait;
use Application\Service\Grade\GradeServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CorpsController extends AbstractActionController {
    use CategorieServiceAwareTrait;
    use CorpsServiceAwareTrait;
    use CorrespondanceServiceAwareTrait;
    use GradeServiceAwareTrait;

    public function indexAction()
    {
        $categories = $this->getCategorieService()->getCategories();
        $corps = $this->getCorpsService()->getCorps();
        $correspondances = $this->getCorrespondanceService()->getCorrespondances();
        $grades = $this->getGradeService()->getGrades();

        return new ViewModel([
            "categories" => $categories,
            "corps" => $corps,
            "correspondances" => $correspondances,
            "grades" => $grades,
        ]);
    }
}