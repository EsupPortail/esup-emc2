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
    
    public function getUsername(string $attribut = "supannAliasLogin") : ?string
    {
        switch ($attribut) {
            case 'supannAliasLogin' : return $this->getSupannAliasLogin();
            case 'uid' : return $this->getUid();
        }
        return null;
    }

    public function getSupannAliasLogin()
    {
        return $this->people->get('supannAliasLogin');
    }

    public function getUid()
    {
        return $this->people->get('uid');
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