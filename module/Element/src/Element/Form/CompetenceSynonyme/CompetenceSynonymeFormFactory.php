<?php

namespace Element\Form\CompetenceSynonyme;

use Element\Service\Competence\CompetenceService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceSynonymeFormFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CompetenceSynonymeForm
    {
        /**
         * @var CompetenceService $competenceService
         * @var CompetenceSynonymeHydrator $hydrator
         */
        $competenceService = $container->get(CompetenceService::class);
        $hydrator = $container->get('FormElementHydrator')->get(CompetenceSynonymeHydrator::class);

        $form = new CompetenceSynonymeForm();
        $form->setCompetenceService($competenceService);
        $form->setHydrator($hydrator);
        return $form;
    }
}
