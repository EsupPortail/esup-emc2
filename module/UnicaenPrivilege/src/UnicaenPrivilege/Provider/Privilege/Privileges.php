<?php

namespace UnicaenPrivilege\Provider\Privilege;

use UnicaenPrivilege\Entity\Db\Privilege;

class Privileges {

    /**
     * Retourne le nom de la ressource associée au privilège donné
     *
     * @param $privilege
     *
     * @return string
     */
    public static function getResourceId( $privilege )
    {
        if ($privilege instanceof Privilege){
            $privilege = $privilege->getFullCode();
        }
        return 'privilege/'.$privilege;
    }

}