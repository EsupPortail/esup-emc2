<?php

namespace Element\View\Helper;

use Element\Service\CompetenceType\CompetenceTypeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceBlocViewHelperFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceBlocViewHelper
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
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