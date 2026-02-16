<?php

namespace EntretienProfessionnel\View\Helper;

use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\Url\UrlService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class AideAgentCampagneViewHelperFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AideAgentCampagneViewHelper
    {
        /**
         * @var CampagneService $campagneService
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var ParametreService $parametreService
         * @var UrlService $urlService
         */
        $campagneService = $container->get(CampagneService::class);
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $parametreService = $container->get(ParametreService::class);
        $urlService = $container->get(UrlService::class);

        $helper = new AideAgentCampagneViewHelper();
        $helper->setCampagneService($campagneService);
        $helper->setEntretienProfessionnelService($entretienProfessionnelService);
        $helper->setParametreService($parametreService);
        $helper->setUrlService($urlService);
        return $helper;
    }
}
