<?php

namespace EntretienProfessionnel\Assertion;

use Application\Constant\RoleConstant;
use Application\Entity\Db\Agent;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Complement\ComplementServiceAwareTrait;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Provider\Privilege\EntretienproPrivileges;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use Structure\Provider\RoleProvider;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class EntretienProfessionnelAssertion extends AbstractAssertion {

    use AgentServiceAwareTrait;
    use ComplementServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use UserServiceAwareTrait;
    use StructureServiceAwareTrait;

    /** @var Agent */
    private $lastAgent;
    /** @var EntretienProfessionnel */
    private $lastEntretien;
    /** @var array */
    private $predicats;

    /**
     * @param EntretienProfessionnel|null $entretienProfessionnel
     * @param Agent|null $connectedAgent
     * @return array
     */
    private function computePredicats(?EntretienProfessionnel $entretienProfessionnel, ?Agent $connectedAgent) : array
    {
        if ($this->lastAgent === $connectedAgent AND $this->lastEntretien === $entretienProfessionnel) return $this->predicats;

        $this->lastAgent = $connectedAgent;
        $this->lastEntretien = $entretienProfessionnel;

        $structures = $entretienProfessionnel->getAgent()->getStructures();
        $this->predicats = [
            'isAgentEntretien'          => $entretienProfessionnel->isAgent($connectedAgent),
            'isResponsableEntretien'    => $entretienProfessionnel->isReponsable($connectedAgent),
            'isGestionnaireStructure'   => $this->getStructureService()->isGestionnaireS($structures, $connectedAgent),
            'isResponsableStructure'    => $this->getStructureService()->isResponsableS($structures, $connectedAgent),
            'isAutoriteStructure'       => $this->getStructureService()->isAutoriteS($structures, $connectedAgent),
            'isSuperieureHierarchique'  => $this->getComplementService()->isSuperieur($connectedAgent, $entretienProfessionnel->getAgent()),
            'isAutoriteHierarchique'    => $this->getComplementService()->isAutorite($connectedAgent, $entretienProfessionnel->getAgent()),
        ];

        return $this->predicats;
    }

    protected function assertEntity(ResourceInterface $entity = null,  $privilege = null) : bool
    {
        /** @var EntretienProfessionnel $entity */
        if (!$entity instanceof EntretienProfessionnel) {
            return false;
        }

        /** @var EntretienProfessionnel $entity */
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);
        $role = $this->getUserService()->getConnectedRole();

        $predicats = $this->computePredicats($entity, $agent);

        switch($privilege) {
            case EntretienproPrivileges::ENTRETIENPRO_AFFICHER :
            case EntretienproPrivileges::ENTRETIENPRO_AJOUTER :
            case EntretienproPrivileges::ENTRETIENPRO_EXPORTER :
            case EntretienproPrivileges::ENTRETIENPRO_MODIFIER :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                    case RoleConstant::DRH:
                    case RoleConstant::OBSERVATEUR:
                        return true;
                    case RoleConstant::PERSONNEL:
                        if ($entity->getEtat()->getCode() === EntretienProfessionnel::ETAT_ACCEPTATION) return false;
                        if ($entity->getEtat()->getCode() === EntretienProfessionnel::ETAT_ACCEPTER) return false;
                        return $predicats['isAgentEntretien'];
                    case RoleProvider::GESTIONNAIRE:
                        return $predicats['isGestionnaireStructure'];
                    case RoleProvider::RESPONSABLE:
                        return $predicats['isResponsableStructure'];
                    case Agent::ROLE_SUPERIEURE:
                        return $predicats['isSuperieureHierarchique'];
                    case Agent::ROLE_AUTORITE:
                        return $predicats['isAutoriteHierarchique'];
                    default:
                        return false;
                }
            case EntretienproPrivileges::ENTRETIENPRO_HISTORISER :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                        return true;
                    case RoleProvider::RESPONSABLE:
                        return (($predicats['isResponsableStructure'] AND $predicats['isResponsableEntretien']) OR $predicats['isAutoriteStructure']);
                    case Agent::ROLE_SUPERIEURE:
                        return $predicats['isSuperieureHierarchique'] AND $predicats['isResponsableEntretien'];
                    case Agent::ROLE_AUTORITE:
                        return $predicats['isAutoriteHierarchique'];
                    default:
                        return false;
            }
            case EntretienproPrivileges::ENTRETIENPRO_VALIDER_RESPONSABLE :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                        return true;
                    case RoleProvider::RESPONSABLE:
                        return $predicats['isResponsableStructure']  AND $predicats['isResponsableEntretien'];
                    case Agent::ROLE_SUPERIEURE:
                        return $predicats['isSuperieureHierarchique'] AND $predicats['isResponsableEntretien'];
                    case Agent::ROLE_AUTORITE:
                        return $predicats['isAutoriteHierarchique'] AND $predicats['isResponsableEntretien'];
                    default:
                        return false;
                }
            case EntretienproPrivileges::ENTRETIENPRO_VALIDER_AGENT :
            case EntretienproPrivileges::ENTRETIENPRO_VALIDER_OBSERVATION :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                        return true;
                    case RoleConstant::PERSONNEL:
                        return $predicats['isAgentEntretien'];
                    default:
                        return false;
                }
            case EntretienproPrivileges::ENTRETIENPRO_VALIDER_DRH :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_FONC:
                    case RoleConstant::ADMIN_TECH:
                    case RoleConstant::DRH:
                        return true;
                    case RoleProvider::RESPONSABLE:
                        return $predicats['isAutoriteStructure'];
                    case Agent::ROLE_AUTORITE:
                        return $predicats['isSuperieureHierarchique'];
                    default:
                        return false;
                }
        }
        return true;
    }

    /**
     * @param AbstractActionController $controller
     * @param $action
     * @param $privilege
     * @return bool
     */
    protected function assertController($controller, $action = null, $privilege = null) : bool
    {

//       var_dump($this->getMvcEvent()->getRouteMatch());
       $entretienId = (($this->getMvcEvent()->getRouteMatch()->getParam('entretien')));
       $entretien = $this->getEntretienProfessionnelService()->getEntretienProfessionnel($entretienId);

        /** @var EntretienProfessionnel $entity */
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);
        $role = $this->getUserService()->getConnectedRole();

        $predicats = [];
        if ($entretien AND $agent) $predicats = $this->computePredicats($entretien, $agent);

        switch ($action) {
            case 'afficher' :
            case 'exporter-crep' :
            case 'exporter-cref' :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_TECH :
                    case RoleConstant::ADMIN_FONC :
                    case RoleConstant::OBSERVATEUR :
                        return true;
                    case Agent::ROLE_AGENT : return $predicats['isAgentEntretien'];
                    case RoleProvider::GESTIONNAIRE : return $predicats['isGestionnaireStructure'];
                    case RoleProvider::RESPONSABLE : return ($predicats['isResponsableStructure'] OR $predicats['isAutoriteStructure']);
                    case Agent::ROLE_SUPERIEURE : return $predicats['isSupe rieureHierarchique'];
                    case Agent::ROLE_AUTORITE : return $predicats['isAutoriteHierarchique'];
                }
                return false;
            case 'creer' :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_TECH :
                    case RoleConstant::ADMIN_FONC :
                    case RoleProvider::RESPONSABLE :
                    case Agent::ROLE_SUPERIEURE :
                    case Agent::ROLE_AUTORITE :
                        return true;
                    case RoleProvider::GESTIONNAIRE : return $predicats['isGestionnaireStructure'];
                }
                return false;
            case 'modifier' :
            case 'historiser' :
            case 'restaurer' :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_TECH :
                    case RoleConstant::ADMIN_FONC :
                        return true;
                    case RoleProvider::RESPONSABLE :  return ($predicats['isResponsableStructure'] OR $predicats['isAutoriteStructure']);
                    case Agent::ROLE_SUPERIEURE : return $predicats['isSuperieureHierarchique'];
                    case Agent::ROLE_AUTORITE : return $predicats['isAutoriteHierarchique'];
                }
                return false;
            case 'renseigner' :
                switch ($role->getRoleId()) {
                    case RoleConstant::ADMIN_TECH :
                    case RoleConstant::ADMIN_FONC :
                        return true;
                    case Agent::ROLE_AGENT : return $predicats['isAgentEntretien'];
                    case RoleProvider::RESPONSABLE : return ($predicats['isResponsableStructure'] OR $predicats['isAutoriteStructure']);
                    case Agent::ROLE_SUPERIEURE : return $predicats['isSuperieureHierarchique'];
                    case Agent::ROLE_AUTORITE : return $predicats['isAutoriteHierarchique'];
                }
                return false;
        }
        return true;
    }
}