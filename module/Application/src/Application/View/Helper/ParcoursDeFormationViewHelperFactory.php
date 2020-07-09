<?php

namespace Application\View\Helper;

use Application\Service\ParcoursDeFormation\ParcoursDeFormationService;
use Interop\Container\ContainerInterface;

class ParcoursDeFormationViewHelperFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ParcoursDeFormationService $parcoursService
         */
        $parcoursService = $container->get(ParcoursDeFormationService::class);

        /** @var ParcoursDeFormationViewHelper $helper */
        $helper = new ParcoursDeFormationViewHelper();
        $helper->setParcoursDeFormationService($parcoursService);
        return $helper;
    }
}