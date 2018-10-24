<?php

namespace Application\Service\MailService;

use UnicaenApp\Options\ModuleOptions;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Transport\TransportInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Resolver\TemplatePathStack;

class MailServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var TransportInterface  */
        $options     = $serviceLocator->get('unicaen-app_module_options');
        /* @var ModuleOptions $options  */
        $mailOptions = $options->getMail();
        $transport = new Smtp(new SmtpOptions($mailOptions['transport_options']));
        $rendererService = $serviceLocator->get('view_renderer');
        $resolver = $rendererService->resolver();
//        $resolver->attach(new TemplatePathStack(['script_paths' => [__DIR__]]));

        /** @var MailService $service */
        $service = new MailService($transport, $mailOptions['redirect_to'], $mailOptions['do_not_send']);
        $service->rendererService = $rendererService;

        return $service;
    }
}