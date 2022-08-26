<?php


namespace Application\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenEvenement\Service\Type\TypeService;
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
         * @var EtatService $etatService
         * @var EtatTypeService $etatTypeService
         * @var TypeService $evenementTypeService
         */
        $privilegeService = $container->get(PrivilegeService::class);
        $templateService = $container->get(TemplateService::class);
        $etatService = $container->get(EtatService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $evenementTypeService = $container->get(TypeService::class);

        $controller = new VerificationController();
        $controller->setPrivilegeService($privilegeService);
        $controller->setTemplateService($templateService);
        $controller->setEtatService($etatService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setTypeService($evenementTypeService);
        return $controller;
    }
}