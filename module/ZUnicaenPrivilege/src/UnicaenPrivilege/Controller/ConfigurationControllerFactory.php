<?php

namespace UnicaenPrivilege\Controller;

use Interop\Container\ContainerInterface;
use UnicaenPrivilege\Form\CategoriePrivilege\CategoriePrivilegeForm;
use UnicaenPrivilege\Form\Privilege\PrivilegeForm;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;

class ConfigurationControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var PrivilegeService $privilegeService */
        $privilegeService = $container->get(PrivilegeService::class);

        /** @var CategoriePrivilegeForm $categoriePrivilegeForm */
        $categoriePrivilegeForm = $container->get('FormElementManager')->get(CategoriePrivilegeForm::class);
        $privilegeForm = $container->get('FormElementManager')->get(PrivilegeForm::class);

        /** @var ConfigurationController $controller */
        $controller = new ConfigurationController();
        $controller->setPrivilegeService($privilegeService);
        $controller->setCategoriePrivilegeForm($categoriePrivilegeForm);
        $controller->setPrivilegeForm($privilegeForm);
        return $controller;
    }
}