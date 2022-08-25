<?php

namespace UnicaenEtat\View\Helper;

use Interop\Container\ContainerInterface;
use Laminas\View\Helper\AbstractHelper;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\Etat\EtatService;

class EtatBadgeViewHelperFactory extends AbstractHelper
{
    /**
     * @param ContainerInterface $container
     * @return EtatBadgeViewHelper
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : EtatBadgeViewHelper
    {
        /**
         * @var EtatService $etatService
         */
        $etatService = $container->get(EtatService::class);

        $helper = new EtatBadgeViewHelper();
        $helper->setEtatService($etatService);
        return $helper;
    }
}