<?php

namespace UnicaenPrivilege\Guard;

use Interop\Container\ContainerInterface;
use UnicaenPrivilege\Provider\Privilege\PrivilegeProviderInterface;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;


class PrivilegeControllerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var PrivilegeProviderInterface $privilegeService */
        $privilegeProvider = $container->get(PrivilegeService::class);

        $rules = []; // NB: l'injection des vraies rules est faite par \BjyAuthorize\Service\BaseProvidersServiceFactory

        $instance = new PrivilegeController($rules, $container);
        $instance->setPrivilegeProvider($privilegeProvider);

        return $instance;
    }
}