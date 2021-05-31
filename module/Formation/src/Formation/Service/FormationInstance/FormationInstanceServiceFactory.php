<?php

namespace Formation\Service\FormationInstance;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Mailing\Service\Mailing\MailingService;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenUtilisateur\Service\User\UserService;

class FormationInstanceServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var EtatService $etatService
         * @var MailingService $mailingService
         * @var ParametreService $parametreService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $etatService = $container->get(EtatService::class);
        $mailingService = $container->get(MailingService::class);
        $parametreService = $container->get(ParametreService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var FormationInstanceService $service
         */
        $service = new FormationInstanceService();
        $service->setEntityManager($entityManager);
        $service->setEtatService($etatService);
        $service->setMailingService($mailingService);
        $service->setParametreService($parametreService);
        $service->setUserService($userService);
        return $service;
    }
}