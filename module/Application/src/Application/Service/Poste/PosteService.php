<?php

namespace Application\Service\Poste;

use Application\Entity\Db\Poste;
use Doctrine\ORM\ORMException;
use RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class PosteService {
    use EntityManagerAwareTrait;

    /** CRUD **********************************************************************************************************/

    public function create(Poste $poste) : Poste
    {
        try {
            $this->getEntityManager()->persist($poste);
            $this->getEntityManager()->flush($poste);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en BD.",0,$e);
        }
        return $poste;
    }

    public function update(Poste $poste) : Poste
    {
        try {
            $this->getEntityManager()->flush($poste);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en BD.",0,$e);
        }
        return $poste;
    }

    public function historise(Poste $poste) : Poste
    {
        $poste->historiser();
        try {
            $this->getEntityManager()->flush($poste);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en BD.",0,$e);
        }
        return $poste;
    }

    public function restore(Poste $poste) : Poste
    {
        $poste->dehistoriser();
        try {
            $this->getEntityManager()->flush($poste);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en BD.",0,$e);
        }
        return $poste;
    }

    public function delete(Poste $poste) : Poste
    {
        try {
            $this->getEntityManager()->remove($poste);
            $this->getEntityManager()->flush($poste);
        }  catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en BD.",0,$e);
        }
        return $poste;
    }


}