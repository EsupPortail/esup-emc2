<?php

namespace UnicaenNote\View\Helper;

use Interop\Container\ContainerInterface;
use UnicaenNote\Service\PorteNote\PorteNoteService;

class PorteNoteViewHelperFactory {

    /**
     * @param ContainerInterface $container
     * @return PorteNoteViewHelper
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var PorteNoteService $porteNoteService
         */
        $porteNoteService = $container->get(PorteNoteService::class);

        $helper = new PorteNoteViewHelper();
        $helper->setPorteNoteService($porteNoteService);
        return $helper;
    }
}