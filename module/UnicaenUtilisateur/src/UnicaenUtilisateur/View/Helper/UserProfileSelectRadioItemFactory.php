<?php

namespace UnicaenUtilisateur\View\Helper;

use Interop\Container\ContainerInterface;
use UnicaenAuthentification\Service\UserContext;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class UserProfileSelectRadioItemFactory
{
    /**
     * Create service
     *
     * @param ContainerInterface $container
     * @return UserProfileSelectRadioItem
     */
    public function __invoke(ContainerInterface $container)
    {
        $serviceLocator     = $container;

        /** @var UserContext $userContextService */
        $userContextService = $serviceLocator->get('AuthUserContext');

        return new UserProfileSelectRadioItem($userContextService);
    }
}