<?php

namespace Application\Service\Perimetre;

use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Structure\Provider\Role\RoleProvider as StructureRoleProvider;
use Structure\Entity\Db\Structure;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenIndicateur\Service\Perimetre\PerimetreServiceInterface;
use UnicaenIndicateur\Service\Perimetre\PerimetreServiceTrait;
use UnicaenUtilisateur\Entity\Db\AbstractRole;
use UnicaenUtilisateur\Entity\Db\AbstractUser;

class PerimetreService implements PerimetreServiceInterface
{
    use PerimetreServiceTrait;
    use StructureServiceAwareTrait;
    /**
     * @param AbstractUser $user
     * @param AbstractRole $role
     * @return array
     */
    public function getPerimetres(AbstractUser $user, AbstractRole $role) : array
    {
        $perimetres = [];

        /** Périmètres de structure $structures ***********************************************************************/
        $structures = [];
        switch ($role->getRoleId()) {
            case AppRoleProvider::ADMIN_TECH:
            case AppRoleProvider::ADMIN_FONC:
            case AppRoleProvider::OBSERVATEUR:
            case AppRoleProvider::DRH:
                $structures = $this->getStructureService()->getStructures();
                break;
            case StructureRoleProvider::RESPONSABLE:
                $listing = $this->getStructureService()->getStructuresByResponsable($user);
                foreach ($listing as $structure) {
                    $all = $this->getStructureService()->getStructuresFilles($structure, true);
                    foreach ($all as $item) $structures[$item->getId()] = $item;
                }
                break;
            case StructureRoleProvider::GESTIONNAIRE:
                $listing = $this->getStructureService()->getStructuresByGestionnaire($user);
                foreach ($listing as $structure) {
                    $all = $this->getStructureService()->getStructuresFilles($structure, true);
                    foreach ($all as $item) $structures[$item->getId()] = $item;
                }
                break;
        }
        $structures = array_map(function (Structure $s) { return 'STRUCTURE_'.$s->getId();}, $structures);
        $perimetres = array_merge($perimetres, $structures);

        //ROLE
        //CAMPAGNE

        return $perimetres;
    }

}