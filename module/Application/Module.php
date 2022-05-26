<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Http\Request as HttpRequest;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;
use Zend\Config\Factory as ConfigFactory;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        /* Active un layout spécial si la requête est de type AJAX. Valable pour TOUS les modules de l'application. */
        $eventManager->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch',
            function (MvcEvent $e) {
                $request = $e->getRequest();
                if ($request instanceof HttpRequest && $request->isXmlHttpRequest()) {
                    $e->getTarget()->layout('layout/ajax.phtml');
                }
            }
        );
        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            "*",
            'authenticate.success',
            array($this, 'onUserLogin'),
            100
        );

    }

    public function onUserLogin( $e ) {
        if (is_string($identity = $e->getIdentity())) {
            // login de l'utilisateur authentifié
            $username = $identity;
            //...
        } else {
            // id de l'utilisateur authentifié dans la table
            $id = $identity;
            //...
        }
        //...
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
