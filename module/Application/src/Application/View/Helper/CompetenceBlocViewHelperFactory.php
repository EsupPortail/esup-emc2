<?php

namespace Application\View\Helper;

use Application\Service\CompetenceType\CompetenceTypeService;
use Interop\Container\ContainerInterface;

class CompetenceBlocViewHelperFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceBlocViewHelper
     */
    public function __invoke(ContainerInterface $container)
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