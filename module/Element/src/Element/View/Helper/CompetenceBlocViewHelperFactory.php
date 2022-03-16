<?php

namespace Element\View\Helper;

use Element\Service\CompetenceType\CompetenceTypeService;
use Interop\Container\ContainerInterface;

class CompetenceBlocViewHelperFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceBlocViewHelper
     */
    public function __invoke(ContainerInterface $container) : CompetenceBlocViewHelper
    {
        /**
         * @var CompetenceTypeService $competenceTypeService
         */
        $competenceTypeService = $container->get(CompetenceTypeService::class);
        $types = $competenceTypeService->getCompetencesTypes('ordre');

        $helper = new CompetenceBlocViewHelper();
        $helper->setCompetencesTypes($types);
        return $helper;
    }
}