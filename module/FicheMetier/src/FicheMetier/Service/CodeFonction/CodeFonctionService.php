<?php

namespace FicheMetier\Service\CodeFonction;

use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\CodeFonction;

class CodeFonctionService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(CodeFonction $codeFonction): void
    {
        $codeFonction->setCode($codeFonction->computeCode());
        $this->getObjectManager()->persist($codeFonction);
        $this->getObjectManager()->flush($codeFonction);
    }

    public function update(CodeFonction $codeFonction): void
    {
        $codeFonction->setCode($codeFonction->computeCode());
        $this->getObjectManager()->flush($codeFonction);
    }

    public function historise(CodeFonction $codeFonction): void
    {
        $codeFonction->historiser();
        $this->getObjectManager()->flush($codeFonction);
    }

    public function restore(CodeFonction $codeFonction): void
    {
        $codeFonction->dehistoriser();
        $this->getObjectManager()->flush($codeFonction);
    }

    public function delete(CodeFonction $codeFonction): void
    {
        $this->getObjectManager()->remove($codeFonction);
        $this->getObjectManager()->flush($codeFonction);
    }

    /** QUERRY ********************************************************************************************************/


}
