<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenEvenement\Service\Type\TypeServiceAwareTrait;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenRenderer\Service\Template\TemplateServiceAwareTrait;
use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;

class VerificationController extends AbstractActionController {
    use PrivilegeServiceAwareTrait;
    use TemplateServiceAwareTrait;
    use EtatServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use TypeServiceAwareTrait;
    use ValidationTypeServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $modules = ['Application', 'Carriere', 'Element', 'EntretienProfessionnel', 'Formation', 'Metier', 'Structure'];

        return new ViewModel([
            'modules' => $modules,
            'templateService' => $this->getTemplateService(),
            'privilegeService' => $this->getPrivilegeService(),
            'etatService' => $this->getEtatService(),
            'etatTypeService' => $this->getEtatTypeService(),
            'evenementTypeService' => $this->getTypeService(),
            'validationTypeService' => $this->getValidationTypeService(),
        ]);
    }
}