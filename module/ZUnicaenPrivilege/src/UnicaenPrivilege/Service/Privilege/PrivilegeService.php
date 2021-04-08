<?php

namespace UnicaenPrivilege\Service\Privilege;

use BjyAuthorize\Provider\Resource\ProviderInterface as ResourceProviderInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenPrivilege\Entity\Db\CategoriePrivilege;
use UnicaenPrivilege\Entity\Db\Privilege;
use UnicaenPrivilege\Entity\Db\PrivilegeInterface;
use UnicaenPrivilege\Provider\Privilege\PrivilegeProviderInterface;
use UnicaenPrivilege\Provider\Privilege\Privileges;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class PrivilegeService implements PrivilegeProviderInterface, ResourceProviderInterface {
    use EntityManagerAwareTrait;

    private $privilegeEntityClass;

    public function setPrivilegeEntityClass($privilegeEntityClass)
    {
        $this->privilegeEntityClass = $privilegeEntityClass;
    }

    /**
     * @var array
     */
    protected $privilegesRoles;

    /**
     * @return array
     */
    public function getPrivilegesWithCategories()
    {
        $qb = $this->getEntityManager()->getRepository(CategoriePrivilege::class)->createQueryBuilder('categorie')
            ->orderBy('categorie.ordre', 'ASC')
        ;
        $categories = $qb->getQuery()->getResult();

        $privileges = [];

        /** @var CategoriePrivilege $categorie */
        foreach ($categories as $categorie) {
            $qb = $this->getEntityManager()->getRepository(Privilege::class)->createQueryBuilder('privilege')
                ->andWhere('privilege.categorie = :id')
                ->setParameter('id', $categorie->getId())
                ->orderBy('privilege.ordre', 'ASC')
            ;

            $result = $qb->getQuery()->getResult();
            if ($result && count($result) > 0) {
                $privileges[$categorie->getLibelle()] = $result;
            }
        }

        return $privileges;
    }

    /**
     * @param Role $role
     * @param Privilege $privilege
     * @return boolean
     */
    public function toggle($role, $privilege) {
        $value = null;
        if ($privilege->hasRole($role)) {
            $privilege->removeRole($role);
            $value = false;
        } else {
            $privilege->addRole($role);
            $value = true;
        }
        try {
            $this->getEntityManager()->flush($privilege);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors du changement du privilège.", $e);
        }
        return $value;
    }

    /**
     * @param $roleId
     * @return Role
     */
    public function getRole($roleId)
    {
        $qb = $this->getEntityManager()->getRepository(Role::class)->createQueryBuilder('role')
            ->andWhere('role.id = :id')
            ->setParameter('id', $roleId)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Role partagent le même identifiant [".$roleId."]",$e);
        }
        return $result;
    }

    /**
     * Retourne un tableau à deux dimentions composé de chaînes de caractère UNIQUEMENT
     *
     * Format du tableau :
     * [
     *   'privilege_a' => ['role_1', ...],
     *   'privilege_b' => ['role_1', 'role_2', ...],
     * ]
     *
     * @return string[][]
     */
    public function getPrivilegesRoles()
    {
        if (null === $this->privilegesRoles) {
            $this->privilegesRoles = [];

            $qb = $this->getEntityManager()->getRepository(Privilege::class)->createQueryBuilder('p')
                ->addSelect('c, r')
                ->join('p.categorie', 'c')
                ->join('p.role', 'r');
            $result = $qb->getQuery()->getResult();

            foreach ($result as $privilege) {
                /* @var $privilege PrivilegeInterface */
                $pr = [];
                foreach ($privilege->getRole() as $role) {
                    /* @var $role RoleInterface */
                    $pr[] = $role->getRoleId();
                }
                $this->privilegesRoles[$privilege->getFullCode()] = $pr;
            }
        }

        $tmp = $this->privilegesRoles;
        return $this->privilegesRoles;
    }

    /**
     * @return array
     */
    public function getResources()
    {
        $resources = [];
        $privileges = array_keys($this->getPrivilegesRoles());
        foreach ($privileges as $privilege) {
            $resources[] = Privileges::getResourceId($privilege);
        }

        return $resources;
    }

    /** Catégorie de privilège ****************************************************************************************/

    /**
     * @param string $champ
     * @param string $ordre
     * @return CategoriePrivilege[]
     */
    public function getCategories($champ = 'libelle', $ordre = 'ASC')
    {
        $qb = $this->getEntityManager()->getRepository(CategoriePrivilege::class)->createQueryBuilder('categorie')
            ->addSelect('privilege')->leftJoin('categorie.privileges', 'privilege')
            ->orderBy('categorie.'. $champ, $ordre)
            ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return CategoriePrivilege
     */
    public function getCategorie($id) {
        $qb = $this->getEntityManager()->getRepository(CategoriePrivilege::class)->createQueryBuilder('categorie')
            ->addSelect('privilege')->leftJoin('categorie.privileges', 'privilege')
            ->andWhere('categorie.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs CategoriePrivilege partagent le même identifiant ['.$id.']', $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return CategoriePrivilege
     */
    public function getRequestedCategorie($controller, $paramName = 'categorie' )
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getCategorie($id);
        return $result;
    }


    /**
     * @param CategoriePrivilege $categorie
     * @return CategoriePrivilege
     */
    public function createCategorie(CategoriePrivilege $categorie)
    {
        try {
            $this->getEntityManager()->persist($categorie);
            $this->getEntityManager()->flush($categorie);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.");
        }
        return $categorie;
    }

    /**
     * @param CategoriePrivilege $categorie
     * @return CategoriePrivilege
     */
    public function updateCategorie(CategoriePrivilege $categorie)
    {
        try {
            $this->getEntityManager()->flush($categorie);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.");
        }
        return $categorie;
    }

    /**
     * @param CategoriePrivilege $categorie
     * @return CategoriePrivilege
     */
    public function deleteCategorie(CategoriePrivilege $categorie)
    {
        try {
            $this->getEntityManager()->remove($categorie);
            $this->getEntityManager()->flush($categorie);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.");
        }
        return $categorie;
    }

    /** Privilege *****************************************************************************************************/

    /**
     * @return Privilege[]
     */
    public function getPrivileges()
    {
        $qb = $this->getEntityManager()->getRepository(Privilege::class)->createQueryBuilder('privilege')
            ->addSelect('categorie')->leftJoin('privilege.categorie', 'categorie')
            ->orderBy('privilege.categorie', 'ASC')
            ->addOrderBy('privilege.ordre', 'ASC')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $id
     * @return Privilege
     */
    public function getPrivilege($id)
    {
        $qb = $this->getEntityManager()->getRepository(Privilege::class)->createQueryBuilder('privilege')
            ->addSelect('categorie')->leftJoin('privilege.categorie', 'categorie')
            ->andWhere('privilege.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs Privilege partagent le même identifiant ['.$id.']', $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Privilege
     */
    public function getRequestedPrivilege($controller, $paramName='privilege')
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getPrivilege($id);
        return $result;
    }

    /**
     * @param Privilege $privilege
     * @return Privilege
     */
    public function createPrivilege($privilege)
    {
        try {
            $this->getEntityManager()->persist($privilege);
            $this->getEntityManager()->flush($privilege);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.");
        }
        return $privilege;
    }

    /**
     * @param Privilege $privilege
     * @return Privilege
     */
    public function updatePrivilege($privilege)
    {
        try {
            $this->getEntityManager()->flush($privilege);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.");
        }
        return $privilege;
    }

    /**
     * @param Privilege $privilege
     * @return Privilege
     */
    public function deletePrivilege($privilege)
    {
        try {
            $this->getEntityManager()->remove($privilege);
            $this->getEntityManager()->flush($privilege);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.");
        }
        return $privilege;
    }

}

