<?php


namespace Application\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;
use UnicaenRenderer\Service\Template\TemplateService;

class VerificationControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return VerificationController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : VerificationController
    {
        /**
         * @var PrivilegeService $privilegeService
         * @var TemplateService $templateService
         */
        $privilegeService = $container->get(PrivilegeService::class);
        $templateService = $container->get(TemplateService::class);

        $controller = new VerificationController();
        $controller->setPrivilegeService($privilegeService);
        $controller->setTemplateService($templateService);
        return $controller;
    }
}