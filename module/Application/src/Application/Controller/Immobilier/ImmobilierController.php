<?php

namespace Application\Controller\Immobilier;

use Application\Service\Immobilier\ImmobilierServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ImmobilierController extends AbstractActionController {
    use ImmobilierServiceAwareTrait;

    public function indexAction()
    {
        $sites = $this->getImmobilierService()->getSites();
        $batiments = $this->getImmobilierService()->getBatiments();

        return new ViewModel([
           'sites' => $sites,
           'batiments' => $batiments,
        ]);
    }

}