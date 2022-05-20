<?php


namespace UnicaenEtat;

use Zend\Config\Factory as ConfigFactory;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

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


    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
