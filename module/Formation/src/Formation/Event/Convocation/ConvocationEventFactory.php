<?php

namespace Formation\Event\Convocation;

use Doctrine\ORM\EntityManager;
use Formation\Service\Session\SessionService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEvenement\Service\Etat\EtatService;
use UnicaenEvenement\Service\Type\TypeService;
use UnicaenParametre\Service\Parametre\ParametreService;

class ConvocationEventFactory
{

    /**
     * @param ContainerInterface $container
     * @return ConvocationEvent
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ConvocationEvent
    {
        /**
         * @var EntityManager $entityManager
         * @var EtatService $etatService
         * @var ParametreService $parametreService
         * @var SessionService $sessionService
         * @var TypeService $typeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $etatService = $container->get(EtatService::class);
        $parametreService = $container->get(ParametreService::class);
        $sessionService = $container->get(SessionService::class);
        $typeService = $container->get(TypeService::class);

        $event = new ConvocationEvent();
        $event->setEntityManager($entityManager);
        $event->setObjectManager($entityManager);
        $event->setEtatEvenementService($etatService);
        $event->setParametreService($parametreService);
        $event->setSessionService($sessionService);
        $event->setTypeService($typeService);

        return $event;
    }
}