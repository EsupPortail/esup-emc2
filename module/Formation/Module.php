<?php

namespace Formation;

use Formation\Controller\AbstractController;
use Laminas\Loader\StandardAutoloader;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Stdlib\Glob;
use Laminas\Config\Factory as ConfigFactory;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $application = $e->getApplication();
        $eventManager = $application->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $sharedEvents = $eventManager->getSharedManager();
        $sharedEvents->attach(AbstractActionController::class, 'dispatch', function($e) {
            /** @var $e MvcEvent */
            $controller = $e->getTarget();
            $hostname = $controller->getRequest()->getUri()->getHost();
            if(preg_match('/mes-formations.*/', $hostname)) {
                $controller->layout('layout/layout-formation');
            }
        }, 100);
    }

    public function getConfig()
    {
        $configInit = [
            __DIR__ . '/config/module.config.php'
        ];
        $configFiles = ArrayUtils::merge(
            $configInit,
            Glob::glob(__DIR__ . '/config/merged/{,*.}{config}.php', Glob::GLOB_BRACE)
        );

        return ConfigFactory::fromFiles($configFiles);
    }

//    public function getViewHelperConfig() {
//        return [
//            'factories' => [
//                'itemsInNavigation' => function($helpers) {
//                    $navigation = $helpers->get('Application')->getServiceManager()->get('Laminas\Navigation\Formation');
//                    return new ItemsInNavigation($navigation);
//                },
//
//            ]
//        ];
//    }

    public function getAutoloaderConfig(): array
    {
        return [
            StandardAutoloader::class => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

}
