<?php

namespace Application\Service\CompetencesRetirees;

use Application\Entity\Db\FicheposteCompetenceRetiree;
use Application\Service\GestionEntiteHistorisationTrait;

class CompetencesRetireesService {
//    use EntityManagerAwareTrait;
//    use UserServiceAwareTrait;
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheposteCompetenceRetiree $competenceRetiree
     * @return FicheposteCompetenceRetiree
     */
    public function create(FicheposteCompetenceRetiree $competenceRetiree) {
        $this->createFromTrait($competenceRetiree);
        return $competenceRetiree;
    }

    /**
     * @param FicheposteCompetenceRetiree $competenceConservee
     * @return FicheposteCompetenceRetiree
     */
    public function update(FicheposteCompetenceRetiree $competenceConservee) {
        $this->updateFromTrait($competenceConservee);
        return $competenceConservee;
    }

    /**
     * @param FicheposteCompetenceRetiree $competenceConservee
     * @return FicheposteCompetenceRetiree
     */
    public function delete(FicheposteCompetenceRetiree $competenceConservee) {
        $this->deleteFromTrait($competenceConservee);
        return $competenceConservee;
    }

}