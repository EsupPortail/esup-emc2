<?php

namespace UnicaenUtilisateur\Service\RechercheIndividu;

interface RechercheIndividuServiceInterface {
    
    /** 
     * @param string $term
     * @return RechercheIndividuResultatInterface[] 
     */
    public function findByTerm(string $term);

    /** 
     * @param $id
     * @return RechercheIndividuResultatInterface 
     */
    public function findById($id);
} 