<?php

namespace UnicaenUtilisateur\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of UserProfileFactory
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class UserProfileFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $helperPluginManager)
    {
        return $this->__invoke($helperPluginManager, '?');
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceLocator  = $container;
        $authUserContext = $serviceLocator->get('authUserContext');

        return new UserProfile($authUserContext);
    }
}