<?php

namespace Application\Service\FormationsRetirees;

use Application\Entity\Db\FicheposteFormationRetiree;
use Application\Service\GestionEntiteHistorisationTrait;

class FormationsRetireesService {
//    use EntityManagerAwareTrait;
//    use UserServiceAwareTrait;
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheposteFormationRetiree $formationConservee
     * @return FicheposteFormationRetiree
     */
    public function create(FicheposteFormationRetiree $formationConservee) {
        $this->createFromTrait($formationConservee);
        return $formationConservee;
    }

    /**
     * @param FicheposteFormationRetiree $formationConservee
     * @return FicheposteFormationRetiree
     */
    public function update(FicheposteFormationRetiree $formationConservee) {
        $this->updateFromTrait($formationConservee);
        return $formationConservee;
    }

    /**
     * @param FicheposteFormationRetiree $formationConservee
     * @return FicheposteFormationRetiree
     */
    public function delete(FicheposteFormationRetiree $formationConservee) {
        $this->deleteFromTrait($formationConservee);
        return $formationConservee;
    }

}