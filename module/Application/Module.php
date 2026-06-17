<?php
/**
 * Laminas Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/LaminasSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Laminas Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Laminas\Http\PhpEnvironment\Request;
use Laminas\Http\Request as HttpRequest;
use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Stdlib\Glob;
use Laminas\Config\Factory as ConfigFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use UnicaenAuthentification\Service\UserContext;
use UnicaenUtilisateur\Entity\Db\AbstractRole;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenUtilisateur\Service\User\UserService;

class Module
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function onBootstrap(MvcEvent $e): void
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        /* Active un layout spécial si la requête est de type AJAX. Valable pour TOUS les modules de l'application. */
        $eventManager->getSharedManager()->attach('Laminas\Mvc\Controller\AbstractActionController', 'dispatch',
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
        $eventManager->attach(MvcEvent::EVENT_ROUTE, [$this, 'handleRolePreference'], 100);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handleRolePreference(MvcEvent $e): void
    {
        $request = $e->getRequest();
        if (!$request instanceof Request) {
            return;
        }

        $queryParams = $request->getQuery()->toArray();
        if (!empty($queryParams['role-prefere']??[])) {
            $roleId = $queryParams['role-prefere'];
            /**
             * @var RoleService $roleService
             * @var UserService $userService
             * @var UserContext $userContextService
             */
            $roleService = $e->getApplication()->getServiceManager()->get(RoleService::class);
            $userService = $e->getApplication()->getServiceManager()->get(UserService::class);
            $userContextService = $e->getApplication()->getServiceManager()->get(UserContext::class);
            /** @var AbstractRole $role */
            $role = $roleService->getRepo()->findOneBy(['roleId' => $roleId]);
            if ($role === null) {
//                throw new RuntimeException("Le rôle [".$roleId."] ne semble pas exister",-1);
                return;
            }
            $user = $userService->getConnectedUser();
          if ($user === null) {
//              throw new RuntimeException("Aucun utilisateur·trice de connecter ", -1);
              return;
          }
            if (!$user->hasRole($role)) {
//                throw new RuntimeException("L'utilisateur·trice ne possède pas le rôle [".$role->getLibelle()."]",-1);
                return;
            }
            $userContextService->setSelectedIdentityRole($role);
        }
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
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
