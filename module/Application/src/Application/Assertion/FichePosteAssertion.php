<?php

namespace Application\Assertion;

use Application\Entity\Db\Agent;
use Application\Entity\Db\FichePoste;
use FichePoste\Provider\Etat\FichePosteEtats;
use Application\Provider\Privilege\FichePostePrivileges;
use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Structure\Provider\Role\RoleProvider as StructureRoleProvider;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Structure\Service\Observateur\ObservateurServiceAwareTrait;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

class FichePosteAssertion extends AbstractAssertion {
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use ObservateurServiceAwareTrait;
    use StructureServiceAwareTrait;

    use PrivilegeServiceAwareTrait;
    use UserServiceAwareTrait;


    private function computePredicats(FichePoste $fichePoste, ?Agent $connectedAgent, ?RoleInterface $role) : array
    {
        $referencedAgent = $fichePoste->getAgent();
        $structures = $referencedAgent->getStructures();

        $predicats = [
            'isAgent'                   => ($role->getRoleId() === AppRoleProvider::AGENT &&  $referencedAgent === $connectedAgent),
            'isResponsable'             => ($role->getRoleId() === StructureRoleProvider::RESPONSABLE && $this->getStructureService()->isResponsableS($structures, $connectedAgent)),
            'isSuperieure'              => ($role->getRoleId() === Agent::ROLE_SUPERIEURE && $this->getAgentSuperieurService()->isSuperieur($referencedAgent, $connectedAgent)),
            'isAutorite'                => ($role->getRoleId() === Agent::ROLE_AUTORITE && $this->getAgentAutoriteService()->isAutorite($referencedAgent, $connectedAgent)),
        ];
        return $predicats;
    }

    public function isScopeCompatible(FichePoste $entretienProfessionnel, ?Agent $connectedAgent, ?RoleInterface $connectedRole, ?array $predicats = null): bool
    {
        $predicats = ($predicats === null)?$this->computePredicats($entretienProfessionnel, $connectedAgent, $connectedRole):$predicats;
        return match ($connectedRole->getRoleId()) {
            AppRoleProvider::ADMIN_FONC, AppRoleProvider::ADMIN_TECH, AppRoleProvider::DRH, AppRoleProvider::OBSERVATEUR  => true,
            AppRoleProvider::AGENT => $predicats['isAgent'],
            StructureRoleProvider::RESPONSABLE => $predicats['isResponsable'],
            Agent::ROLE_SUPERIEURE => $predicats['isSuperieure'],
            Agent::ROLE_AUTORITE => $predicats['isAutorite'],
            default => false,
        };
    }

    protected function computeAssertion(ResourceInterface $entity = null,  $privilege = null) : bool
    {
        if (! $entity instanceof FichePoste) {
            return false;
        }

        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();
        if (!$this->getPrivilegeService()->checkPrivilege($privilege, $role)) return false;


        $connectedAgent = $this->getAgentService()->getAgentByUser($user);
        $etatCode = ($entity->getEtatActif())?$entity->getEtatActif()->getType()->getCode():null;

        switch($privilege) {
            case FichePostePrivileges::FICHEPOSTE_AFFICHER :
                switch ($role->getRoleId()) {
                    case AppRoleProvider::AGENT:
                        $isAgent = ($entity->getAgent()->getUtilisateur() === $user);
                        return $isAgent AND ($etatCode === FichePosteEtats::ETAT_CODE_OK OR $etatCode === FichePosteEtats::ETAT_CODE_SIGNEE);
                    case StructureRoleProvider::OBSERVATEUR:
                        $structures = ($entity->getAgent())?$entity->getAgent()->getStructures():[];
                        $isObservateur = $this->getObservateurService()->isObservateur($structures, $user);
                        return $isObservateur;
                    default:
                        return $this->isScopeCompatible($entity, $connectedAgent, $role);
                }
            case FichePostePrivileges::FICHEPOSTE_AJOUTER :
            case FichePostePrivileges::FICHEPOSTE_MODIFIER :
            case FichePostePrivileges::FICHEPOSTE_HISTORISER :
                // REMARQUE on ne peut plus agir sur une fiche signée et plus active
                if ($etatCode === FichePosteEtats::ETAT_CODE_SIGNEE) return false;
                return $this->isScopeCompatible($entity, $connectedAgent, $role);
            case FichePostePrivileges::FICHEPOSTE_DETRUIRE :
            case FichePostePrivileges::FICHEPOSTE_ETAT :
            case FichePostePrivileges::FICHEPOSTE_VALIDER_RESPONSABLE :
            case FichePostePrivileges::FICHEPOSTE_VALIDER_AGENT :
                return $this->isScopeCompatible($entity, $connectedAgent, $role);
        }
        return true;
    }

    protected function assertEntity(ResourceInterface $entity = null,  $privilege = null) : bool {
        return $this->computeAssertion($entity, $privilege);
    }

    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        //to remove copium here (on attrape comme on peut la fiche de poste : fiche-poste, fiche, ... )
        $ficheId = (($this->getMvcEvent()->getRouteMatch()->getParam('fiche-poste')));
        if ($ficheId === null) $ficheId = (($this->getMvcEvent()->getRouteMatch()->getParam('fiche')));
        $fiche = $this->getFichePosteService()->getFichePoste($ficheId);

        if ($fiche === null) return true;
        return match ($action) {
            'afficher', 'export', 'exporter' => $this->computeAssertion($fiche, FichePostePrivileges::FICHEPOSTE_AFFICHER),
            'ajouter', 'dupliquer' => $this->computeAssertion($fiche, FichePostePrivileges::FICHEPOSTE_AJOUTER),
            'modifier-information-poste' => $this->computeAssertion($fiche, FichePostePrivileges::FICHEPOSTE_MODIFIER_POSTE),
            'editer', 'associer-agent', 'associer-titre', 'editer-rifseep', 'editer-specificite',
            'ajouter-fiche-metier', 'retirer-fiche-metier', 'modifier-fiche-metier', 'modifier-repartition',
            'selectionner-activite', 'selectionner-mission', 'selectionner-applications-retirees', 'selectionner-competences-retirees', 'selectionner-formations-retirees',
            'changer-etat' => $this->computeAssertion($fiche, FichePostePrivileges::FICHEPOSTE_ETAT),
            'valider', 'revoquer'=> ($this->computeAssertion($fiche, FichePostePrivileges::FICHEPOSTE_VALIDER_AGENT) || $this->computeAssertion($fiche, FichePostePrivileges::FICHEPOSTE_VALIDER_RESPONSABLE)),
            'historiser', 'restaurer' => $this->computeAssertion($fiche, FichePostePrivileges::FICHEPOSTE_HISTORISER),
            'detruire'=> $this->computeAssertion($fiche, FichePostePrivileges::FICHEPOSTE_DETRUIRE),
            default => true,
        };
    }
}