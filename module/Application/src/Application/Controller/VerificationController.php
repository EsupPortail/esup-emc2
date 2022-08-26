<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenEvenement\Service\Type\TypeServiceAwareTrait;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenRenderer\Service\Template\TemplateServiceAwareTrait;

class VerificationController extends AbstractActionController {
    use PrivilegeServiceAwareTrait;
    use TemplateServiceAwareTrait;
    use EtatServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use TypeServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $modules = ['EntretienProfessionnel', 'Formation'];

        return new ViewModel([
            'modules' => $modules,
            'templateService' => $this->getTemplateService(),
            'privilegeService' => $this->getPrivilegeService(),
            'etatService' => $this->getEtatService(),
            'etatTypeService' => $this->getEtatTypeService(),
            'evenementTypeService' => $this->getTypeService(),
        ]);
    }
}