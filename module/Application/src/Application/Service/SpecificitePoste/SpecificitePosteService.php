<?php

namespace Application\Service\SpecificitePoste;

use Application\Entity\Db\SpecificitePoste;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class SpecificitePosteService {
    use EntityManagerAwareTrait;

    /** GESTION ENTITE ************************************************************************************************/

    /**
     * @param SpecificitePoste $specificite
     * @return SpecificitePoste
     */
    public function create(SpecificitePoste $specificite)
    {
        try {
            $this->getEntityManager()->persist($specificite);
            $this->getEntityManager()->flush($specificite);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la création de la spécificité du poste.", $e);
        }
        return $specificite;
    }

    /**
     * @param SpecificitePoste $specificite
     * @return SpecificitePoste
     */
    public function update(SpecificitePoste $specificite)
    {
        try {
            $this->getEntityManager()->flush($specificite);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la mise à jour de la spécificité du poste.", $e);
        }
        return $specificite;
    }

    /**
     * @param SpecificitePoste $specificite
     */
    public function delete(SpecificitePoste $specificite)
    {
        try {
            $this->getEntityManager()->remove($specificite);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de l'effacement de la spécificité du poste.", $e);
        }
    }

}