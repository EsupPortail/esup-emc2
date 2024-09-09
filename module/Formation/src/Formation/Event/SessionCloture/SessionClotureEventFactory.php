<?php

namespace Formation\Event\SessionCloture;

use Doctrine\ORM\EntityManager;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Service\Session\SessionService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEvenement\Service\Etat\EtatService;
use UnicaenEvenement\Service\Type\TypeService;
use UnicaenParametre\Entity\Db\Parametre;
use UnicaenParametre\Service\Parametre\ParametreService;

class SessionClotureEventFactory
{

    /**
     * @param ContainerInterface $container
     * @return SessionClotureEvent
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SessionClotureEvent
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

        $event = new SessionClotureEvent();
        $event->setEntityManager($entityManager);
        $event->setObjectManager($entityManager);
        $event->setEtatEvenementService($etatService);
        $event->setParametreService($parametreService);
        $event->setSessionService($sessionService);
        $event->setTypeService($typeService);

        return $event;
    }
}