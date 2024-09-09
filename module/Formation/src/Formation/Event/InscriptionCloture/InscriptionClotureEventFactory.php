<?php

namespace Formation\Event\InscriptionCloture;

use Doctrine\ORM\EntityManager;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Service\Notification\NotificationService;
use Formation\Service\Session\SessionService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEvenement\Service\Etat\EtatService;
use UnicaenEvenement\Service\Type\TypeService;
use UnicaenParametre\Entity\Db\Parametre;
use UnicaenParametre\Service\Parametre\ParametreService;

class InscriptionClotureEventFactory
{

    /**
     * @param ContainerInterface $container
     * @return InscriptionClotureEvent
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): InscriptionClotureEvent
    {
        /**
         * @var EntityManager $entityManager
         * @var SessionService $sessionService
         * @var NotificationService $notificationService
         * @var ParametreService $parametreService
         * @var EtatService $etatService
         * @var TypeService $typeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $notificationService = $container->get(NotificationService::class);
        $parametreService = $container->get(ParametreService::class);
        $sessionService = $container->get(SessionService::class);
        $etatService = $container->get(EtatService::class);
        $typeService = $container->get(TypeService::class);

        $event = new InscriptionClotureEvent();
        $event->setObjectManager($entityManager);
        $event->setEtatEvenementService($etatService);
        $event->setNotificationService($notificationService);
        $event->setParametreService($parametreService);
        $event->setSessionService($sessionService);
        $event->setTypeService($typeService);

        /** @var Parametre $deadline */
        $deadline = $container->get(ParametreService::class)->getParametreByCode(FormationParametres::TYPE, FormationParametres::AUTO_FERMETURE);
        if ($deadline === null) throw new RuntimeException("Parametre non défini [" . FormationParametres::TYPE . "," . FormationParametres::AUTO_FERMETURE . "]");
        $event->setDeadline($deadline->getValeur());

        return $event;
    }
}