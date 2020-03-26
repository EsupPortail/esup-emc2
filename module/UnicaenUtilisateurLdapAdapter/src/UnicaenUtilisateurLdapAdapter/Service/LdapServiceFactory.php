<?php

namespace UnicaenUtilisateurLdapAdapter\Service;

use Interop\Container\ContainerInterface;
use UnicaenLdap\Service\People;


class LdapServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return LdapService
     */
    public function __invoke(ContainerInterface $container) {

        /**
         * @var People $ldapService
         */
        $ldapService = $container->get(People::class);

        /** @var LdapService $service */
        $service = new LdapService();
        $service->setLdapPeopleService($ldapService);

        return $service;
    }
}