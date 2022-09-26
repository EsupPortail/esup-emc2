<?php

namespace Formation\Controller;

use Doctrine\ORM\EntityManager;
use Formation\Form\EnqueteCategorie\EnqueteCategorieForm;
use Formation\Form\EnqueteQuestion\EnqueteQuestionForm;
use Formation\Form\EnqueteReponse\EnqueteReponseForm;
use Formation\Service\EnqueteCategorie\EnqueteCategorieService;
use Formation\Service\EnqueteQuestion\EnqueteQuestionService;
use Formation\Service\EnqueteReponse\EnqueteReponseService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class EnqueteReponseControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return EnqueteReponseController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : EnqueteReponseController
    {
        /**
         * @var EnqueteCategorieService $enqueteCategorieService
         * @var EnqueteReponseService $enqueteReponseService
         */
        $enqueteCategorieService = $container->get(EnqueteCategorieService::class);
        $enqueteReponseService = $container->get(EnqueteReponseService::class);

        $controller = new EnqueteReponseController();
        $controller->setEnqueteCategorieService($enqueteCategorieService);
        $controller->setEnqueteReponseService($enqueteReponseService);

        return $controller;
    }
}