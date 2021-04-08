<?php

namespace UnicaenPrivilege\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
//use UnicaenAuth\Entity\Db\PrivilegeInterface;
//use UnicaenAuth\Entity\Db\RoleInterface;
//use UnicaenAuth\Entity\Db\CategoriePrivilege;
use UnicaenUtilisateur\Entity\Db\RoleInterface;

use UnicaenUtilisateur\Entity\Db\Role;

/**
 * Privilege
 */
class Privilege implements  PrivilegeInterface //extends AbstractPrivilege
{
    const DROIT_ROLE_VISUALISATION          = 'droit-role-visualisation';
    const DROIT_ROLE_EDITION                = 'droit-role-edition';
    const DROIT_PRIVILEGE_VISUALISATION     = 'droit-privilege-visualisation';
    const DROIT_PRIVILEGE_EDITION           = 'droit-privilege-edition';

    /** @var integer */
    protected $id;
    /** @var string */
    protected $code;
    /** @var string */
    protected $libelle;
    /** @var integer */
    protected $ordre;
    /** @var CategoriePrivilege */
    protected $categorie;
    /** @var ArrayCollection */
    protected $role;

    public function __construct()
    {
        //parent::__construct();
        $this->role = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Privilege
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return Privilege
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * @param int $ordre
     * @return Privilege
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
        return $this;
    }

    /**
     * @return CategoriePrivilege
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param CategoriePrivilege $categorie
     * @return Privilege
     */
    public function setCategorie(CategoriePrivilege $categorie = null)
    {
        $this->categorie = $categorie;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param RoleInterface $role
     * @return Privilege
     */
    public function addRole(RoleInterface $role)
    {
        $this->role->add($role);
        return $this;
    }
    /**
     * @param RoleInterface $role
     * @return Privilege
     */
    public function removeRole(RoleInterface $role)
    {
        $this->role->removeElement($role);
        return $this;
    }

    /**
     * @param RoleInterface $role
     * @return bool
     */
    public function hasRole($role)
    {
        $roles = $this->getRole()->toArray();
        /** @var Role $roleLF */
        foreach ($roles as $roleLF) {
            $a = $role->getId();
            $b = $roleLF->getId();
            if ($a == $b) return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getFullCode()
    {
        return $this->getCategorie()->getCode() . '-' . $this->getCode();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        //TODO
        return 'not yet';
    }
}

