<?php

namespace Application\Controller;

use Application\Service\Metier\MetierServiceAwareTrait;
use DateTime;
use UnicaenApp\View\Model\CsvModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class RessourceRhController extends AbstractActionController {
    /** Trait utilisés pour les services */
    use MetierServiceAwareTrait;

    public function indexAction()
    {
        return new ViewModel([]);
    }

}