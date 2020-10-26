<?php

namespace Formation\Service\FormationInstanceFrais;

use Formation\Entity\Db\FormationInstanceFrais;
use Application\Service\GestionEntiteHistorisationTrait;

class FormationInstanceFraisService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES **********************************************************************************/

    /**
     * @param FormationInstanceFrais $frais
     * @return FormationInstanceFrais
     */
    public function create(FormationInstanceFrais $frais)
    {
        $this->createFromTrait($frais);
        return $frais;
    }

    /**
     * @param FormationInstanceFrais $frais
     * @return FormationInstanceFrais
     */
    public function update(FormationInstanceFrais $frais)
    {
        $this->updateFromTrait($frais);
        return $frais;
    }

    /**
     * @param FormationInstanceFrais $frais
     * @return FormationInstanceFrais
     */
    public function historise(FormationInstanceFrais $frais)
    {
        $this->historiserFromTrait($frais);
        return $frais;
    }

    /**
     * @param FormationInstanceFrais $frais
     * @return FormationInstanceFrais
     */
    public function restore(FormationInstanceFrais $frais)
    {
        $this->restoreFromTrait($frais);
        return $frais;
    }

    /**
     * @param FormationInstanceFrais $frais
     * @return FormationInstanceFrais
     */
    public function delete(FormationInstanceFrais $frais)
    {
        $this->deleteFromTrait($frais);
        return $frais;
    }
}