<?php

namespace UnicaenPrivilege\Entity\Db;

use Doctrine\Common\Collections\Collection;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
//use UnicaenAuthentification\Entity\Db\CategoriePrivilege;

interface PrivilegeInterface
{
    /**
     * @param string $code
     * @return self
     */
    public function setCode($code);

    /**
     * @return string
     */
    public function getCode();

    /**
     * @return string
     */
    public function getFullCode();

    /**
     * @param string $libelle
     * @return self
     */
    public function setLibelle($libelle);

    /**
     * @return string
     */
    public function getLibelle();

    /**
     * @return integer
     */
    function getOrdre();

    /**
     * @param integer $ordre
     * @return self
     */
    function setOrdre($ordre);

    /**
     * @return integer
     */
    public function getId();

    /**
     * @param CategoriePrivilege $categorie
     * @return self
     */
    public function setCategorie(CategoriePrivilege $categorie = null);

    /**
     * @return CategoriePrivilege
     */
    public function getCategorie();

    /**
     * @param RoleInterface $role
     * @return self
     */
    public function addRole(RoleInterface $role);

    /**
     * @param RoleInterface $role
     */
    public function removeRole(RoleInterface $role);

    /**
     * @return Collection
     */
    public function getRole();

    /**
     * @return string
     */
    public function __toString();
}