<?php

namespace Mailing\Service\Mailing;

use Doctrine\ORM\EntityManager;
use Mailing\Service\MailType\MailTypeService;
use UnicaenApp\Options\ModuleOptions;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Transport\TransportInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
// use Zend\View\Resolver\TemplatePathStack;

class MailingServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var TransportInterface  */
        $options     = $serviceLocator->get('unicaen-app_module_options');
        /* @var ModuleOptions $options  */
        $mailOptions = $options->getMail();
        $transport = new Smtp(new SmtpOptions($mailOptions['transport_options']));
        $rendererService = $serviceLocator->get('ViewRenderer');
        $resolver = $rendererService->resolver();
//        $resolver->attach(new TemplatePathStack(['script_paths' => [__DIR__]]));

        /**
         * @var EntityManager $entityManager
         * @var MailTypeService $mailTypeService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $mailTypeService = $serviceLocator->get(MailTypeService::class);

        /** @var MailingService $service */
        $service = new MailingService($transport, $mailOptions['redirect_to'], $mailOptions['do_not_send']);
        $service->setEntityManager($entityManager);
        $service->setMailTypeService($mailTypeService);
        $service->rendererService = $rendererService;

        return $service;
    }
}
