<?php

namespace Application\Controller\Fonction;

use Application\Service\Fonction\FonctionServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FonctionController extends AbstractActionController {
    use FonctionServiceAwareTrait;


    public function indexAction()
    {
        $fonctions = $this->getFonctionService()->getFonctions();

        return new ViewModel([
            'fonctions' => $fonctions,
        ]);
    }
}