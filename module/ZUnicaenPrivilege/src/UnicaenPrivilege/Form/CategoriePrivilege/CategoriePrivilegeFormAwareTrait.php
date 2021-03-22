<?php

namespace UnicaenPrivilege\Form\CategoriePrivilege;

trait CategoriePrivilegeFormAwareTrait {

    /** @var CategoriePrivilegeForm  */
    private $categoriePrivilegeForm;

    /**
     * @return CategoriePrivilegeForm
     */
    public function getCategoriePrivilegeForm()
    {
        return $this->categoriePrivilegeForm;
    }

    /**
     * @param CategoriePrivilegeForm $categoriePrivilegeForm
     * @return CategoriePrivilegeForm
     */
    public function setCategoriePrivilegeForm($categoriePrivilegeForm)
    {
        $this->categoriePrivilegeForm = $categoriePrivilegeForm;
        return $this->categoriePrivilegeForm;
    }


}