<?php 

namespace UnicaenUtilisateur\Service\RechercheIndividu;

interface RechercheIndividuResultatInterface {

    public function getId();
    
    /** @return string */
    public function getUsername();

    /** @return string */
    public function getDisplayname();

    /** @return string */
    public function getEmail();
}