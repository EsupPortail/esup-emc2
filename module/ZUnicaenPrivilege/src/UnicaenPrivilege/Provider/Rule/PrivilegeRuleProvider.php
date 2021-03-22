<?php

namespace UnicaenPrivilege\Provider\Rule;

use BjyAuthorize\Provider\Rule\ProviderInterface;
use UnicaenPrivilege\Provider\Privilege\PrivilegeProviderAwareTrait;
use UnicaenPrivilege\Provider\Privilege\Privileges;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Rule provider based on a given array of rules
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PrivilegeRuleProvider implements ProviderInterface
{
    use PrivilegeProviderAwareTrait;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var array
     */
    protected $rules;

    /**
     * @param array                   $config
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(array $config, ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        $this->config = $config;
    }

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function processConfig()
    {
        $this->rules = $this->makeRules($this->config);
    }

    public function makeRules(array $config)
    {
        $pr = $this->getPrivilegeProvider()->getPrivilegesRoles();

        foreach ($config as $grant => $rules) {
            foreach ($rules as $index => $rule) {
                if (is_array($rule)) {
                    $privileges = isset($rule['privileges']) ? (array)$rule['privileges'] : [];
                    $ressources = $rule['resources'];
                    $assertion  = isset($rule['assertion']) ? $rule['assertion'] : null;

                    $bjyRoles   = isset($rule['roles']) ? (array)$rule['roles'] : [];
                    foreach ($pr as $privilege => $roles) {
                        if (in_array($privilege, $privileges)) {
                            $bjyRoles = array_unique(array_merge($bjyRoles, $roles));
                        }
                    }
                    $bjyRule = [
                        $bjyRoles,
                        $ressources,
                        $privileges,
                    ];
                    if ($assertion) $bjyRule[3] = $assertion;

                    $config[$grant][$index] = $bjyRule;

                }
            }
        }

        // Mise en place des droits pour tester les privilèges en tant que ressources
        $rules = $config;
        if (!isset($rules['allow'])) $rules['allow'] = [];
        foreach ($pr as $privilege => $roles) {
            $rules[empty($roles) ? 'deny' : 'allow'][] = [
                $roles,
                Privileges::getResourceId($privilege),
            ];
        }

        return $rules;
    }



    /**
     * {@inheritDoc}
     */
    public function getRules()
    {
        return $this->rules;
    }
}
