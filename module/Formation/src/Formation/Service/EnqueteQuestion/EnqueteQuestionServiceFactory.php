<?php

namespace Formation\Service\EnqueteQuestion;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class EnqueteQuestionServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return EnqueteQuestionService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : EnqueteQuestionService
    {
        /**
         * @var EntityManager $entitymanager
         */
        $entitymanager = $container->get('doctrine.entitymanager.orm_default');

        $service = new EnqueteQuestionService();
        $service->setObjectManager($entitymanager);
        return $service;
    }
}