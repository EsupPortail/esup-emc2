<?php

namespace Mailing\Service\Mailing;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenApp\Options\ModuleOptions;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Transport\TransportInterface;

class MailingServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var TransportInterface  */
        $options     = $container->get('unicaen-app_module_options');
        /* @var ModuleOptions $options  */
        $mailOptions = $options->getMail();
        $transport = new Smtp(new SmtpOptions($mailOptions['transport_options']));
        $rendererService = $container->get('ViewRenderer');
        $resolver = $rendererService->resolver();
//        $resolver->attach(new TemplatePathStack(['script_paths' => [__DIR__]]));

        /**
         *  @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var MailingService $service */
        $service = new MailingService($transport, $mailOptions['redirect_to'], $mailOptions['do_not_send']);
        $service->setEntityManager($entityManager);
        $service->rendererService = $rendererService;

        return $service;
    }
}
