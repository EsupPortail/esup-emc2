<?php

namespace Application\Service\NiveauEnveloppe;

use Application\Entity\Db\NiveauEnveloppe;
use Application\Service\GestionEntiteHistorisationTrait;

class NiveauEnveloppeService {

    use GestionEntiteHistorisationTrait;

    /** GESTIONS DES ENTITES ******************************************************************************************/

    /**
     * @param NiveauEnveloppe $metierNiveau
     * @return NiveauEnveloppe
     */
    public function create(NiveauEnveloppe $metierNiveau) : NiveauEnveloppe
    {
        $this->createFromTrait($metierNiveau);
        return $metierNiveau;
    }

    /**
     * @param NiveauEnveloppe $metierNiveau
     * @return NiveauEnveloppe
     */
    public function update(NiveauEnveloppe $metierNiveau) : NiveauEnveloppe
    {
        $this->updateFromTrait($metierNiveau);
        return $metierNiveau;
    }

    /**
     * @param NiveauEnveloppe $metierNiveau
     * @return NiveauEnveloppe
     */
    public function historise(NiveauEnveloppe $metierNiveau) : NiveauEnveloppe
    {
        $this->historiserFromTrait($metierNiveau);
        return $metierNiveau;
    }

    /**
     * @param NiveauEnveloppe $metierNiveau
     * @return NiveauEnveloppe
     */
    public function restore(NiveauEnveloppe $metierNiveau) : NiveauEnveloppe
    {
        $this->restoreFromTrait($metierNiveau);
        return $metierNiveau;
    }

    /**
     * @param NiveauEnveloppe $metierNiveau
     * @return NiveauEnveloppe
     */
    public function delete(NiveauEnveloppe $metierNiveau) : NiveauEnveloppe
    {
        $this->deleteFromTrait($metierNiveau);
        return $metierNiveau;
    }
}