<?php

namespace UnicaenUtilisateur\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of UserInfoFactory
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class UserInfoFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $helperPluginManager)
    {
        return $this->__invoke($helperPluginManager, '?');
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceLocator  = $container;
        $authUserContext = $serviceLocator->get('authUserContext');
        $mapper          = $serviceLocator->get('ldap_structure_mapper');

        $helper = new UserInfo($authUserContext);
        $helper->setMapperStructure($mapper);

        return $helper;
    }
}