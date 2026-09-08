<?php

namespace FicheMetier\Service\FicheMetierCategorie;

use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\FicheMetierCategorie;

class FicheMetierCategorieService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(FicheMetierCategorie $ficheMetierCategorie): void
    {
        $this->getObjectManager()->persist($ficheMetierCategorie);
        $this->getObjectManager()->flush($ficheMetierCategorie);
    }

    public function update(FicheMetierCategorie $ficheMetierCategorie): void
    {
        $this->getObjectManager()->flush($ficheMetierCategorie);
    }

    public function historise(FicheMetierCategorie $ficheMetierCategorie): void
    {
        $ficheMetierCategorie->historiser();
        $this->getObjectManager()->flush($ficheMetierCategorie);
    }

    public function restore(FicheMetierCategorie $ficheMetierCategorie): void
    {
        $ficheMetierCategorie->dehistoriser();
        $this->getObjectManager()->flush($ficheMetierCategorie);
    }

    public function delete(FicheMetierCategorie $ficheMetierCategorie): void
    {
        $this->getObjectManager()->remove($ficheMetierCategorie);
        $this->getObjectManager()->flush($ficheMetierCategorie);
    }

    /** QUERYING ******************************************************************************************************/

    /** FACADE ********************************************************************************************************/
}
