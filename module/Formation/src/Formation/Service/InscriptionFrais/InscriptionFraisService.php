<?php

namespace Formation\Service\InscriptionFrais;

use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\InscriptionFrais;

class InscriptionFraisService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES **********************************************************************************/

    /**
     * @param InscriptionFrais $frais
     * @return InscriptionFrais
     */
    public function create(InscriptionFrais $frais) : InscriptionFrais
    {
        $this->getObjectManager()->persist($frais);
        $this->getObjectManager()->flush($frais);
        return $frais;
    }

    /**
     * @param InscriptionFrais $frais
     * @return InscriptionFrais
     */
    public function update(InscriptionFrais $frais) : InscriptionFrais
    {
        $this->getObjectManager()->flush($frais);
        return $frais;
    }

    /**
     * @param InscriptionFrais $frais
     * @return InscriptionFrais
     */
    public function historise(InscriptionFrais $frais) : InscriptionFrais
    {
        $frais->historiser();
        $this->getObjectManager()->flush($frais);
        return $frais;
    }

    /**
     * @param InscriptionFrais $frais
     * @return InscriptionFrais
     */
    public function restore(InscriptionFrais $frais) : InscriptionFrais
    {
        $frais->dehistoriser();
        $this->getObjectManager()->flush($frais);
        return $frais;
    }

    /**
     * @param InscriptionFrais $frais
     * @return InscriptionFrais
     */
    public function delete(InscriptionFrais $frais) : InscriptionFrais
    {
        $this->getObjectManager()->remove($frais);
        $this->getObjectManager()->flush($frais);
        return $frais;
    }
}