<?php

namespace Metier\Service\MetierNiveau;

use Application\Service\GestionEntiteHistorisationTrait;
use Metier\Entity\Db\MetierNiveau;

class MetierNiveauService {

    use GestionEntiteHistorisationTrait;

    /** GESTIONS DES ENTITES ******************************************************************************************/

    /**
     * @param MetierNiveau $metierNiveau
     * @return MetierNiveau
     */
    public function create(MetierNiveau $metierNiveau) : MetierNiveau
    {
        $this->createFromTrait($metierNiveau);
        return $metierNiveau;
    }

    /**
     * @param MetierNiveau $metierNiveau
     * @return MetierNiveau
     */
    public function update(MetierNiveau $metierNiveau) : MetierNiveau
    {
        $this->updateFromTrait($metierNiveau);
        return $metierNiveau;
    }

    /**
     * @param MetierNiveau $metierNiveau
     * @return MetierNiveau
     */
    public function historise(MetierNiveau $metierNiveau) : MetierNiveau
    {
        $this->historiserFromTrait($metierNiveau);
        return $metierNiveau;
    }

    /**
     * @param MetierNiveau $metierNiveau
     * @return MetierNiveau
     */
    public function restore(MetierNiveau $metierNiveau) : MetierNiveau
    {
        $this->restoreFromTrait($metierNiveau);
        return $metierNiveau;
    }

    /**
     * @param MetierNiveau $metierNiveau
     * @return MetierNiveau
     */
    public function delete(MetierNiveau $metierNiveau) : MetierNiveau
    {
        $this->deleteFromTrait($metierNiveau);
        return $metierNiveau;
    }
}