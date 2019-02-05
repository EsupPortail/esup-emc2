<?php

namespace Application\View\Helper;

use Octopus\Service\Immobilier\ImmobilierService;
use Zend\View\HelperPluginManager;

class PosteViewHelperFactory {

    public function __invoke(HelperPluginManager $manager)
    {
        /** @var ImmobilierService $immobilierService */
        $immobilierService = $manager->getServiceLocator()->get(ImmobilierService::class);

        /** @var PosteViewHelper $helper */
        $helper = new PosteViewHelper();
        $helper->setImmobiliserService($immobilierService);
        return $helper;
    }
}