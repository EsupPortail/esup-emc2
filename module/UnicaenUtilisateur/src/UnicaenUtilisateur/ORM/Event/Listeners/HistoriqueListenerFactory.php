<?php

namespace UnicaenUtilisateur\ORM\Event\Listeners;

use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of MouchardServiceFactory
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class HistoriqueListenerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this->__invoke($serviceLocator, '?');
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var AuthenticationService $authenticationService */
        $authenticationService = $container->get('Zend\Authentication\AuthenticationService');

        $listener = new HistoriqueListener();
        $listener->setAuthenticationService($authenticationService);

        return $listener;
    }
}
