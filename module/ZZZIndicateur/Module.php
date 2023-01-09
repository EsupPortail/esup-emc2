<?php
/**
 * Laminas Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/LaminasSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Laminas Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Indicateur;

use Laminas\Config\Factory as ConfigFactory;
use Laminas\Console\Adapter\AdapterInterface as Console;
use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Stdlib\Glob;

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
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConsoleUsage(Console $console)
    {
        return [
            'indicateur-refresh'  => "Rafraichir la liste des indicateurs",

            'indicateur-notifier' => "Notifier les personnes abonnées à des indicateurs avec les données du dernier rafraichissement",
        ];
    }
}
