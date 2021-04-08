<?php

namespace UnicaenUtilisateur\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of UserStatusFactory
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class UserStatusFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $helperPluginManager)
    {
        return $this->__invoke($helperPluginManager, '?');
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userContext = $container->get('authUserContext');

        return new UserStatus($userContext);
    }
}