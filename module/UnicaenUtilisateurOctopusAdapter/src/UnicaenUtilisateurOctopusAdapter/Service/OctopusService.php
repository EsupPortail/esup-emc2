<?php

namespace UnicaenUtilisateurOctopusAdapter\Service;

use Octopus\Service\Individu\IndividuServiceAwareTrait;
use UnicaenUtilisateur\Service\RechercheIndividu\RechercheIndividuResultatInterface;
use UnicaenUtilisateur\Service\RechercheIndividu\RechercheIndividuServiceInterface;
use UnicaenUtilisateurOctopusAdapter\Entity\OctopusIndividu;

class OctopusService implements RechercheIndividuServiceInterface {
    use IndividuServiceAwareTrait;


    /** Fonctions associées à l'interface RechercheIndividuServiceInterface */

    /**
     * @param $id
     * @return \UnicaenUtilisateur\Service\RechercheIndividu\RechercheIndividuResultatInterface|void
     */
    public function findById($id)
    {
        $individu = $this->getIndividuService()->getIndividu($id);
        $p = new OctopusIndividu();
        $p->setIndividu($individu);
        return $p;
    }

    /**
     * @param string $term
     * @return RechercheIndividuResultatInterface[]
     */
    public function findByTerm(string $term)
    {
        $individus = $this->getIndividuService()->getIndividusByTerm($term);
        $result = [];
        foreach ($individus as $individu) {
            $octopusIndividu = new OctopusIndividu();
            $octopusIndividu->setIndividu($individu);
            $result[] = $octopusIndividu;
        }
        return $result;
    }
}