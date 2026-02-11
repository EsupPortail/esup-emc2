<?php

namespace EntretienProfessionnel\Assertion;

use Application\Entity\Db\Agent;
use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use DateTime;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Provider\Privilege\EntretienproPrivileges;
use EntretienProfessionnel\Provider\Role\RoleProvider as EntretienProfessionnelRoleProvider;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Observateur\ObservateurServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Structure\Provider\Role\RoleProvider;
use Structure\Service\Observateur\ObservateurStructureServiceAwareTrait;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class EntretienProfessionnelAssertion extends AbstractAssertion {

    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use ObservateurServiceAwareTrait;
    use ObservateurStructureServiceAwareTrait;
    use PrivilegeServiceAwareTrait;
    use UserServiceAwareTrait;
    use StructureServiceAwareTrait;

    private ?Agent $lastAgent = null;
    private ?EntretienProfessionnel $lastEntretien = null;
    private ?Role $lastRole = null;
    private ?array $predicats = null;


    private bool $BLOCAGE_COMPTERENDU = false;
    private bool $BLOCAGE_VALIDATION = false;

    public function setBLOCAGECOMPTERENDU(bool $BLOCAGE_COMPTERENDU): void
    {
        $this->BLOCAGE_COMPTERENDU = $BLOCAGE_COMPTERENDU;
    }

    public function setBLOCAGEVALIDATION(bool $BLOCAGE_VALIDATION): void
    {
        $this->BLOCAGE_VALIDATION = $BLOCAGE_VALIDATION;
    }


    /**
     * @param EntretienProfessionnel|null $entretienProfessionnel
     * @param Agent|null $connectedAgent
     * @param Role|null $role
     * @return array
     */
    private function computePredicats(?EntretienProfessionnel $entretienProfessionnel, ?Agent $connectedAgent, ?RoleInterface $role) : array
    {
        if (
            $this->lastAgent === $connectedAgent AND
            $this->lastEntretien === $entretienProfessionnel AND
            $this->lastRole === $role
        ) return $this->predicats;

//        var_dump("Recalcul des prédicats : ".(new \DateTime())->format('H:i:s:u '));
        $this->lastAgent = $connectedAgent;
        $this->lastEntretien = $entretienProfessionnel;
        $this->lastRole = $role;

        $structures = $entretienProfessionnel->getAgent()->getStructures();
        $this->predicats = [
            'isAgentEntretien'          => $entretienProfessionnel->isAgent($connectedAgent),
            'isResponsableEntretien'    => $entretienProfessionnel->isResponsable($connectedAgent),
            'isResponsableStructure'    => ($role->getRoleId() === RoleProvider::RESPONSABLE) && $this->getStructureService()->isResponsableS($structures, $connectedAgent),
            'isAutoriteStructure'       => $this->getStructureService()->isAutoriteS($structures, $connectedAgent),
            'isSuperieureHierarchique'  => $connectedAgent !== null && $this->getAgentSuperieurService()->isSuperieur($entretienProfessionnel->getAgent(), $connectedAgent),
            'isAutoriteHierarchique'    =>  $connectedAgent !== null && $this->getAgentAutoriteService()->isAutorite($entretienProfessionnel->getAgent(), $connectedAgent),
        ];
//        var_dump("Fin du calcul : ".(new \DateTime())->format('H:i:s:u '));
        return $this->predicats;
    }

    public function isScopeCompatible(EntretienProfessionnel $entretienProfessionnel, ?Agent $connectedAgent, ?RoleInterface $connectedRole, ?array $predicats = null): bool
    {
        if ($predicats === null) $predicats = $this->computePredicats($entretienProfessionnel, $connectedAgent, $connectedRole);

        $structures = $entretienProfessionnel->getAgent()->getStructures();

        return match ($connectedRole->getRoleId()) {
            AppRoleProvider::ADMIN_FONC, AppRoleProvider::ADMIN_TECH, AppRoleProvider::DRH, AppRoleProvider::OBSERVATEUR  => true,
            AppRoleProvider::AGENT => $predicats['isAgentEntretien'],
            RoleProvider::RESPONSABLE => $predicats['isResponsableStructure'],
            Agent::ROLE_SUPERIEURE => $predicats['isSuperieureHierarchique'],
            Agent::ROLE_AUTORITE => $predicats['isAutoriteHierarchique'],
            EntretienProfessionnelRoleProvider::OBSERVATEUR => $this->getObservateurService()->isObservateur($entretienProfessionnel, $connectedAgent->getUtilisateur()),
            RoleProvider::OBSERVATEUR => $this->getObservateurStructureService()->isObservateur($structures, $connectedAgent->getUtilisateur()),
            default => false,
        };
    }

    static public function isPeriodeCompatible(EntretienProfessionnel $entretienProfessionnel): bool
    {
        $campagne = $entretienProfessionnel->getCampagne();
        $now = new DateTime();

        if ($campagne !== null AND $campagne->getDateDebut() !== null AND $now < $campagne->getDateDebut()) {
            return false;
        }
        if ($campagne !== null AND $campagne->getDateFin() !== null AND $now > $campagne->getDateFin()) {
            return false;
        }
        return true;
    }

    public function computeAssertion(EntretienProfessionnel $entretien, string $privilege) : bool
    {
        /** @var ?RoleInterface $role */
        $role = $this->getUserService()->getConnectedRole();
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);

        if ($agent === null) {
            $agent = new Agent();
            $agent->setId(-1);
            $agent->setUtilisateur($user);
        }



        if (!$this->getPrivilegeService()->checkPrivilege($privilege, $role)) return false;

        $now = new DateTime();

        $grades = $entretien->getAgent()->getGradesActifs($entretien->getDateEntretien());

        //todo faire une vraiment méthode
        $inhibition = false;
        foreach ($grades as $grade) {
            if ($grade->getCorps()->isSuperieurAsAutorite()) {
                $inhibition = true;
                break;
            }
        }
        if (empty($agent->getAutorites())) {
            $inhibition = true;
        }
        if (count($entretien->getAgent()->getAutorites()) === 1 && count($entretien->getAgent()->getSuperieurs()) === 1 && current($entretien->getAgent()->getAutorites())->getAutorite() === current($entretien->getAgent()->getSuperieurs())->getSuperieur())
        {
            $inhibition = true;
        }


        $predicats = $this->computePredicats($entretien, $agent, $role);
        switch($privilege) {
            case EntretienproPrivileges::ENTRETIENPRO_RENSEIGNER :
            case EntretienproPrivileges::ENTRETIENPRO_REINITIALISER :
                /** Attention ceci est pour utiliser afin d'ouvrir la voie lors de recours */
                $recours = $entretien->getRecoursActif();
                if ($recours and $recours->isEntretienModifiable()) {
                    if ($role->getRoleId() === AppRoleProvider::DRH) return true;
                    if ($role->getRoleId() === AppRoleProvider::ADMIN_TECH) return true;
                    if ($role->getRoleId() === AppRoleProvider::ADMIN_FONC) return true;
                }
                switch ($role->getRoleId()) {
                    case Agent::ROLE_AGENT :
                        $etatOk = $entretien->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER);
                        $dateOk = $now < $entretien->getDateEntretien();
                        $scope = $this->isScopeCompatible($entretien, $agent, $role, $predicats);
                        $blocage = ($this->BLOCAGE_COMPTERENDU AND !$this->isPeriodeCompatible($entretien));
                        return $etatOk && $dateOk && $scope && !$blocage;
                    case Agent::ROLE_SUPERIEURE:
                        $etatOk = $entretien->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER) || $entretien->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTATION);
                        $isResponsable = ($agent === $entretien->getResponsable());
                        $blocage = ($this->BLOCAGE_COMPTERENDU AND !$this->isPeriodeCompatible($entretien));
                        return $etatOk  && $isResponsable && !$blocage;
                    default :
                        $etatOk = $entretien->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER) || $entretien->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTATION);
                        $scope = $this->isScopeCompatible($entretien, $agent, $role, $predicats);
                        $blocage = ($this->BLOCAGE_COMPTERENDU AND !$this->isPeriodeCompatible($entretien));
                        return $etatOk && $scope && !$blocage;
                }
            case EntretienproPrivileges::ENTRETIENPRO_EXPORTER :
                if ($entretien->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER) ||
                    $entretien->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTATION)) return false;
                return $this->isScopeCompatible($entretien, $agent, $role, $predicats);
            case EntretienproPrivileges::ENTRETIENPRO_CONVOQUER :
            case EntretienproPrivileges::ENTRETIENPRO_MODIFIER :
                switch ($role->getRoleId()) {
                    case AppRoleProvider::ADMIN_FONC:
                    case AppRoleProvider::ADMIN_TECH:
                    case AppRoleProvider::DRH:
                    case AppRoleProvider::OBSERVATEUR:
                        return true;
                    case AppRoleProvider::AGENT:
                        if ($entretien->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER)) return false;
                        if ($entretien->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTATION)) return false;
                        return $predicats['isAgentEntretien'];
                    case RoleProvider::RESPONSABLE:
                        return $predicats['isResponsableStructure'];
                    case Agent::ROLE_SUPERIEURE:
                        return $predicats['isSuperieureHierarchique'];
                    case Agent::ROLE_AUTORITE:
                        return $predicats['isAutoriteHierarchique'];
                    case EntretienProfessionnelRoleProvider::OBSERVATEUR:
                        return $this->isScopeCompatible($entretien, $agent, $role);
                    default:
                        return false;
                }
            case EntretienproPrivileges::ENTRETIENPRO_HISTORISER :
                return match ($role->getRoleId()) {
                    AppRoleProvider::ADMIN_FONC, AppRoleProvider::ADMIN_TECH => true,
                    RoleProvider::RESPONSABLE => (($predicats['isResponsableStructure'] and $predicats['isResponsableEntretien']) or $predicats['isAutoriteStructure']),
                    Agent::ROLE_SUPERIEURE => $predicats['isSuperieureHierarchique'] and $predicats['isResponsableEntretien'],
                    Agent::ROLE_AUTORITE => $predicats['isAutoriteHierarchique'],
                    EntretienProfessionnelRoleProvider::OBSERVATEUR => $this->isScopeCompatible($entretien, $agent, $role),
                    default => false,
                };
            case EntretienproPrivileges::ENTRETIENPRO_AFFICHER :
                return $this->isScopeCompatible($entretien, $agent, $role, $predicats);
            case EntretienproPrivileges::ENTRETIENPRO_VALIDER_RESPONSABLE :
            case EntretienproPrivileges::ENTRETIENPRO_VALIDER_AGENT :
            case EntretienproPrivileges::ENTRETIENPRO_VALIDER_OBSERVATION :
                if ($this->BLOCAGE_VALIDATION AND !$this->isPeriodeCompatible($entretien)) return false;
                return $this->isScopeCompatible($entretien, $agent, $role, $predicats);
            case EntretienproPrivileges::ENTRETIENPRO_VALIDER_DRH :
                if ($this->BLOCAGE_VALIDATION AND !$this->isPeriodeCompatible($entretien)) return false;
                $value =  match ($role->getRoleId()) {
                    AppRoleProvider::ADMIN_FONC, AppRoleProvider::ADMIN_TECH, AppRoleProvider::DRH => true,
                    RoleProvider::RESPONSABLE, Agent::ROLE_AUTORITE => ($predicats['isAutoriteHierarchique'] && ($inhibition || !$predicats['isResponsableEntretien'])),
                    default => false,
                };
                return $value;
        }
        return true;
    }

    protected function assertEntity(ResourceInterface $entity = null,  $privilege = null) : bool
    {


        /** @var EntretienProfessionnel $entity */
        if (!$entity instanceof EntretienProfessionnel) {
            return false;
        }

        return $this->computeAssertion($entity, $privilege);
    }

    /**
     * @param AbstractActionController $controller
     * @param $action
     * @param $privilege
     * @return bool
     */
    protected function assertController($controller, $action = null, $privilege = null) : bool
    {

        //to remove copium here
        $entretienId = (($this->getMvcEvent()->getRouteMatch()->getParam('entretien-professionnel')));
        if ($entretienId === null) $entretienId = (($this->getMvcEvent()->getRouteMatch()->getParam('entretien')));
        $entretien = $this->getEntretienProfessionnelService()->getEntretienProfessionnel($entretienId);

        if ($entretien === null) return true;
        return match ($action) {
            'exporter-crep', 'exporter-cref' => $this->computeAssertion($entretien, EntretienproPrivileges::ENTRETIENPRO_EXPORTER),
            'creer' => $this->computeAssertion($entretien, EntretienproPrivileges::ENTRETIENPRO_CONVOQUER),
            'modifier', 'historiser', 'restaurer' => $this->computeAssertion($entretien, EntretienproPrivileges::ENTRETIENPRO_MODIFIER),
            'acceder' => $this->computeAssertion($entretien, EntretienproPrivileges::ENTRETIENPRO_AFFICHER),
            'reinitialiser' => $this->computeAssertion($entretien, EntretienproPrivileges::ENTRETIENPRO_REINITIALISER),
            default => true,
        };
    }
}
