<?php

namespace Mailing\Service\Mailing;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Mailing\Service\MailType\MailTypeService;
use UnicaenApp\Options\ModuleOptions;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Contenu\ContenuService;
use UnicaenUtilisateur\Service\User\UserService;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Transport\TransportInterface;

class MailingServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var TransportInterface  */
        $config = $container->get('Configuration')['unicaen-mail'];
        /* @var ModuleOptions $options  */
        $transport = new Smtp(new SmtpOptions($config['transport_options']));
        $rendererService = $container->get('ViewRenderer');

        /**
         * @var EntityManager $entityManager
         * @var ContenuService $contenuService
         * @var MailTypeService $mailTypeService
         * @var ParametreService $parametreService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $contenuService = $container->get(ContenuService::class);
        $mailTypeService = $container->get(MailTypeService::class);
        $parametreService = $container->get(ParametreService::class);
        $userService = $container->get(UserService::class);

        /** @var MailingService $service */
        $service = new MailingService($transport, $config['redirect_to'], $config['do_not_send']);
        $service->setEntityManager($entityManager);
        $service->setContenuService($contenuService);
        $service->setMailTypeService($mailTypeService);
        $service->setParametreService($parametreService);
        $service->setUserService($userService);
        $service->rendererService = $rendererService;

        return $service;
    }
}
