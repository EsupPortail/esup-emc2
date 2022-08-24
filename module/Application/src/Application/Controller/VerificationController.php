<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenRenderer\Service\Template\TemplateServiceAwareTrait;

class VerificationController extends AbstractActionController {
    use PrivilegeServiceAwareTrait;
    use TemplateServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $modules = ['Application', 'Formation', 'Structure'];

        return new ViewModel([
            'modules' => $modules,
            'templateService' => $this->getTemplateService(),
            'privilegeService' => $this->getPrivilegeService(),
        ]);
    }
}