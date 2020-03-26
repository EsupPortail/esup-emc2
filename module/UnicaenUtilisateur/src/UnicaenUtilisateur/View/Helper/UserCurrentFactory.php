<?php

namespace UnicaenUtilisateur\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of UserCurrentFactory
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class UserCurrentFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $helperPluginManager)
    {
        return $this->__invoke($helperPluginManager, '?');
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authUserContext = $container->get('authUserContext');

        return new UserCurrent($authUserContext);
    }
}