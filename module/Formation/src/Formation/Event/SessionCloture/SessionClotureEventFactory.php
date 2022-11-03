<?php

namespace Formation\Event\DemandeRetour;

use Doctrine\ORM\EntityManager;
use Formation\Event\SessionCloture\SessionClotureEvent;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Service\FormationInstance\FormationInstanceService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenApp\Exception\RuntimeException;
use UnicaenParametre\Entity\Db\Parametre;
use UnicaenParametre\Service\Parametre\ParametreService;

class SessionClotureEventFactory {

    /**
     * @param ContainerInterface $container
     * @return SessionClotureEvent
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SessionClotureEvent
    {
        /**
         * @var EntityManager $entityManager
         * @var FormationInstanceService $sessionService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $sessionService = $container->get(FormationInstanceService::class);

        $event = new SessionClotureEvent();
        $event->setEntityManager($entityManager);
        $event->setFormationInstanceService($sessionService);

        /** @var Parametre $deadline */
        $deadline = $container->get(ParametreService::class)->getParametreByCode(FormationParametres::TYPE, FormationParametres::AUTO_CLOTURE);
        if ($deadline === null) throw new RuntimeException("Parametre non défini [".FormationParametres::TYPE.",".FormationParametres::AUTO_CLOTURE."]");
        $event->setDeadline($deadline->getValeur());

        return $event;
    }
}