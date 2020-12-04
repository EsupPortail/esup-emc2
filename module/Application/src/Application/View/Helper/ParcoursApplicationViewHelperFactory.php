<?php

namespace Application\View\Helper;

use Application\Service\ParcoursDeFormation\ParcoursDeFormationService;
use Interop\Container\ContainerInterface;

class ParcoursApplicationViewHelperFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ParcoursDeFormationService $parcoursService
         */
        $parcoursService = $container->get(ParcoursDeFormationService::class);

        /** @var ParcoursApplicationViewHelper $helper */
        $helper = new ParcoursApplicationViewHelper();
        $helper->setParcoursDeFormationService($parcoursService);
        return $helper;
    }
}