<?php

namespace Autoform\View\Helper;

use Autoform\Service\Champ\ChampService;
use Interop\Container\ContainerInterface;

class ChampAsResultHelperFactory {

    public function __invoke(ContainerInterface $container) {

        /**
         * @var ChampService $champService
         */
        $champService = $container->get(ChampService::class);

        /** @var ChampAsResultHelper */
        $helper = new ChampAsResultHelper();
        $helper->setChampService($champService);
        return $helper;
    }
}