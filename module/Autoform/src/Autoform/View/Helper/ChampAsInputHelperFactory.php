<?php

namespace Autoform\View\Helper;

use Autoform\Service\Champ\ChampService;
use Interop\Container\ContainerInterface;

class ChampAsInputHelperFactory {

    public function __invoke(ContainerInterface $container) {

        /**
         * @var ChampService $champService
         */
        $champService = $container->get(ChampService::class);

        /** @var ChampAsInputHelper */
        $helper = new ChampAsInputHelper();
        $helper->setChampService($champService);
        return $helper;
    }
}