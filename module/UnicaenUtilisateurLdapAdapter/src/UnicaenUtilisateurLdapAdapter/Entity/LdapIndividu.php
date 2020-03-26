<?php

namespace UnicaenUtilisateurLdapAdapter\Entity;

use UnicaenLdap\Entity\People;
use UnicaenUtilisateur\Service\RechercheIndividu\RechercheIndividuResultatInterface;

class LdapIndividu implements RechercheIndividuResultatInterface {
    
    /** @var \UnicaenLdap\Entity\People */
    private $people;

    /**
     * @return \UnicaenLdap\Entity\People
     */
    public function getPeople()
    {
        return $this->people;
    }

    /**
     * @param \UnicaenLdap\Entity\People $people
     * @return People
     */
    public function setPeople($people)
    {
        $this->people = $people;
        return $this;
    }
    
    public function getId()
    {
        return $this->people->getId();
    }
    
    public function getUsername()
    {
        return $this->people->get('supannAliasLogin');
    }

    public function getDisplayname()
    {
        $tmp_name = $this->people->get('sn');
        if (!is_string($tmp_name)) $tmp_name = implode("-",$this->people->get('sn'));
        return $tmp_name . " ". $this->people->get('givenName');
    }

    public function getEmail()
    {
        return $this->people->get('mail');
    }
}