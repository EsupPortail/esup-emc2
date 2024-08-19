<?php

namespace Formation\Event\DemandeRetour;

use Doctrine\ORM\EntityManager;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Service\Session\SessionService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenApp\Exception\RuntimeException;
use UnicaenParametre\Entity\Db\Parametre;
use UnicaenParametre\Service\Parametre\ParametreService;

class DemandeRetourEventFactory
{

    /**
     * @param ContainerInterface $container
     * @return DemandeRetourEvent
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): DemandeRetourEvent
    {
        /**
         * @var EntityManager $entityManager
         * @var SessionService $sessionService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $sessionService = $container->get(SessionService::class);

        $event = new DemandeRetourEvent();
        $event->setEntityManager($entityManager);
        $event->setSessionService($sessionService);

        /** @var Parametre $deadline */
        $deadline = $container->get(ParametreService::class)->getParametreByCode(FormationParametres::TYPE, FormationParametres::AUTO_RETOUR);
        if ($deadline === null) throw new RuntimeException("Parametre non défini [" . FormationParametres::TYPE . "," . FormationParametres::AUTO_RETOUR . "]");
        $event->setDeadline($deadline->getValeur());

        return $event;
    }
}