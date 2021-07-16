<?php

namespace Application\Controller;

use Application\Form\Niveau\NiveauFormAwareTrait;
use Application\Service\Niveau\NiveauServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class NiveauController extends AbstractActionController {
    use NiveauServiceAwareTrait;
    use NiveauFormAwareTrait;

    public function indexAction()
    {
        $niveaux = $this->getNiveauService()->getNiveaux();
        return new ViewModel([
            'niveaux' => $niveaux,
        ]);
    }



}