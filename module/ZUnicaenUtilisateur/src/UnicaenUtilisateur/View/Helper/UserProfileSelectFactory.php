<?php

namespace UnicaenUtilisateur\View\Helper;

use Interop\Container\ContainerInterface;
use UnicaenAuthentification\Service\UserContext;

/**
 * Description of UserProfileFactory
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class UserProfileSelectFactory
{
    /**
     * Create service
     *
     * @param ContainerInterface $container
     * @return UserProfileSelect
     */
    public function __invoke(ContainerInterface $container)
    {
        $serviceLocator     = $container;

        /** @var UserContext $userContextService */
        $userContextService = $serviceLocator->get('AuthUserContext');

        return new UserProfileSelect($userContextService);
    }
}