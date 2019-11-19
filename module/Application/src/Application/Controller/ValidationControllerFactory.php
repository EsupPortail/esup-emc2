<?php

namespace Application\Controller;

use Application\Form\Validation\ValidationForm;
use Application\Form\ValidationDemande\ValidationDemandeForm;
use Application\Service\Domaine\DomaineService;
use Application\Service\FicheMetier\FicheMetierService;
use Application\Service\Validation\ValidationDemandeService;
use Application\Service\Validation\ValidationService;
use Application\Service\Validation\ValidationTypeService;
use Application\Service\Validation\ValidationValeurService;
use Interop\Container\ContainerInterface;
use Mailing\Service\Mailing\MailingService;
use Utilisateur\Service\User\UserService;

class ValidationControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var DomaineService $domaineService
         * @var FicheMetierService $ficheMetierService
         * @var ValidationService $validationService
         * @var ValidationTypeService $validationTypeService
         * @var ValidationValeurService $validationValeurService
         * @var ValidationDemandeService $validationDemandeService
         * @var UserService $userService
         * @var MailingService $mailingService
         *
         * @var ValidationForm $validationForm
         * @var ValidationDemandeForm $validationDemandeForm
         */
        $domaineService = $container->get(DomaineService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $validationService = $container->get(ValidationService::class);
        $validationTypeService = $container->get(ValidationTypeService::class);
        $validationValeurService = $container->get(ValidationValeurService::class);
        $validationDemandeService = $container->get(ValidationDemandeService::class);
        $mailingService = $container->get(MailingService::class);
        $userService = $container->get(UserService::class);

        $validationForm = $container->get('FormElementManager')->get(ValidationForm::class);
        $validationDemandeForm = $container->get('FormElementManager')->get(ValidationDemandeForm::class);

        /** @var ValidationController $controller */
        $controller = new ValidationController();
        $controller->setDomaineService($domaineService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setValidationService($validationService);
        $controller->setValidationTypeService($validationTypeService);
        $controller->setValidationValeurService($validationValeurService);
        $controller->setValidationDemandeService($validationDemandeService);
        $controller->setMailingService($mailingService);
        $controller->setUserService($userService);

        $controller->setValidationForm($validationForm);
        $controller->setValidationDemandeForm($validationDemandeForm);
        return $controller;
    }
}