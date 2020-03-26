<?php

namespace UnicaenUtilisateurLdapAdapter\Service;

use UnicaenLdap\Exception;
use UnicaenLdap\Filter\People  as PeopleFilter;
use UnicaenLdap\Service\People as PeopleService;
use UnicaenLdap\Entity\People  as PeopleEntity;
use UnicaenLdap\Service\LdapPeopleServiceAwareTrait;
use UnicaenUtilisateurLdapAdapter\Entity\LdapIndividu;
use UnicaenUtilisateur\Exception\RuntimeException;
use UnicaenUtilisateur\Service\RechercheIndividu\RechercheIndividuResultatInterface;
use UnicaenUtilisateur\Service\RechercheIndividu\RechercheIndividuServiceInterface;

class LdapService implements RechercheIndividuServiceInterface {
    use LdapPeopleServiceAwareTrait; 
    
    /**
     * @param $id
     * @return RechercheIndividuResultatInterface
     */
    public function findById($id)
    {
        /**
         * @var PeopleEntity $people
         */
        $people = $this->ldapPeopleService->get($id);

        $p = new LdapIndividu();
        $p->setPeople($people);
        return $p;
    }

    /**
     * @param string $term
     * @return RechercheIndividuResultatInterface[]
     */
    public function findByTerm(string $term)
    {
        $people = null;
        $filter = PeopleFilter::orFilter(
            PeopleFilter::username($term),
            PeopleFilter::nameContains($term)
        );
        /** @var PeopleService $ldapService */
        try {
            $people = $this->ldapPeopleService->search($filter);
        } catch (Exception $e) {
            throw new RuntimeException("Un exception ldap est survenue :", $e);
        }

        $res = [];
        /** @var PeopleEntity $peep */
        foreach ($people as $peep) {
            $p = new LdapIndividu();
            $p->setPeople($peep);
            $res[] = $p;
        }
        return $res;
    }
}