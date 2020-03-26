<?php

namespace UnicaenUtilisateur\View\Helper;

use UnicaenAuthentification\Service\UserContext;
use Zend\I18n\View\Helper\AbstractTranslatorHelper;

/**
 * Classe mère des aides de vue concernant l'utilisateur connecté.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class UserAbstract extends AbstractTranslatorHelper
{
    protected $userContext;

    /**
     * @var array
     */
    static protected $currentIdentity;

    /**
     * Constructeur.
     *
     * @param UserContext $userContext
     */
    public function __construct(UserContext $userContext = null)
    {
        if (null !== $userContext) {
            $this->setUserContext($userContext);
        }
    }

    /**
     * Get UserContext Service.
     *
     * @return UserContext
     */
    public function getUserContext()
    {
        return $this->userContext;
    }

    /**
     * Set UserContext.
     *
     * @param UserContext $userContext
     * @return UserAbstract
     */
    public function setUserContext($userContext)
    {
        $this->userContext = $userContext;
        return $this;
    }

    /**
     * Retourne les données d'identité courante éventuelle.
     *
     * @param string $preferedKey
     * @return mixed
     */
    public function getIdentity($preferedKey = null)
    {
        if (static::$currentIdentity !== null) {
            return static::$currentIdentity;
        }

        if (! ($identity = $this->getUserContext()->getIdentity())) {
            return null;
        }

        if (is_array($identity)) {
            $keys = ['ldap', 'db', 'shib'];
            if ($preferedKey) {
                // on met la clé spécifiée en tête de liste
                $keys = array_merge(($tmp = [$preferedKey]), array_diff($keys, $tmp));
            }
            $found = null;
            foreach ($keys as $key) {
                if (array_key_exists($key, $identity) && $identity[$key] !== null) {
                    $found = $identity[$key];
                    break;
                }
            }
            $identity = $found;
        }

        static::$currentIdentity = $identity;

        return static::$currentIdentity;
    }
}