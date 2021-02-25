<?php

namespace UnicaenGlossaire\View\Helper;

use Interop\Container\ContainerInterface;
use UnicaenGlossaire\Service\Definition\DefinitionService;
use UnicaenGlossaire\View\Helper\DictionnaireGenerationViewHelper;

class DictionnaireGenerationViewHelperFactory {

    /**
     * @param ContainerInterface $container
     * @return DictionnaireGenerationViewHelper
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var DefinitionService $definitionService
         */
        $definitionService = $container->get(DefinitionService::class);
        $definitions = $definitionService->getDefinitions();

        $helper = new DictionnaireGenerationViewHelper();
        $helper->setDefinitions($definitions);
        return $helper;
    }
}