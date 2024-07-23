<?php

namespace Formation\Controller;

use Doctrine\ORM\EntityManager;
use Formation\Form\EnqueteCategorie\EnqueteCategorieForm;
use Formation\Form\EnqueteQuestion\EnqueteQuestionForm;
use Formation\Form\EnqueteReponse\EnqueteReponseForm;
use Formation\Service\EnqueteCategorie\EnqueteCategorieService;
use Formation\Service\EnqueteQuestion\EnqueteQuestionService;
use Formation\Service\EnqueteReponse\EnqueteReponseService;
use Formation\Service\Inscription\InscriptionService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class EnqueteQuestionControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return EnqueteQuestionController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): EnqueteQuestionController
    {
        /**
         * @var EntityManager $entityManager
         * @var EnqueteCategorieService $enqueteCategorieService
         * @var EnqueteReponseService $enqueteReponseService
         * @var InscriptionService $inscriptionService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $enqueteCategorieService = $container->get(EnqueteCategorieService::class);
        $enqueteQuestionService = $container->get(EnqueteQuestionService::class);
        $enqueteReponseService = $container->get(EnqueteReponseService::class);
        $inscriptionService = $container->get(InscriptionService::class);

        /**
         * @var EnqueteCategorieForm $enqueteCategorieForm
         * @var EnqueteQuestionForm $enqueteQuestionForm
         * @var EnqueteReponseForm $enqueteReponseForm
         */
        $enqueteCategorieForm = $container->get('FormElementManager')->get(EnqueteCategorieForm::class);
        $enqueteQuestionForm = $container->get('FormElementManager')->get(EnqueteQuestionForm::class);
        $enqueteReponseForm = $container->get('FormElementManager')->get(EnqueteReponseForm::class);

        $controller = new EnqueteQuestionController();
        $controller->setObjectManager($entityManager);
        $controller->setEnqueteCategorieService($enqueteCategorieService);
        $controller->setEnqueteQuestionService($enqueteQuestionService);
        $controller->setEnqueteReponseService($enqueteReponseService);
        $controller->setInscriptionService($inscriptionService);
        $controller->setEnqueteCategorieForm($enqueteCategorieForm);
        $controller->setEnqueteQuestionForm($enqueteQuestionForm);
        $controller->setEnqueteReponseForm($enqueteReponseForm);

        return $controller;
    }
}