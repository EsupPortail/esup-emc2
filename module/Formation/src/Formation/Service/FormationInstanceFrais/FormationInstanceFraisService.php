<?php

namespace Formation\Service\FormationInstanceFrais;

use Doctrine\ORM\Exception\ORMException;
use Formation\Entity\Db\InscriptionFrais;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class FormationInstanceFraisService
{
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES **********************************************************************************/

    /**
     * @param InscriptionFrais $frais
     * @return InscriptionFrais
     */
    public function create(InscriptionFrais $frais) : InscriptionFrais
    {
        try {
            $this->getEntityManager()->persist($frais);
            $this->getEntityManager()->flush($frais);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $frais;
    }

    /**
     * @param InscriptionFrais $frais
     * @return InscriptionFrais
     */
    public function update(InscriptionFrais $frais) : InscriptionFrais
    {
        try {
            $this->getEntityManager()->flush($frais);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $frais;
    }

    /**
     * @param InscriptionFrais $frais
     * @return InscriptionFrais
     */
    public function historise(InscriptionFrais $frais) : InscriptionFrais
    {
        try {
            $frais->historiser();
            $this->getEntityManager()->flush($frais);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $frais;
    }

    /**
     * @param InscriptionFrais $frais
     * @return InscriptionFrais
     */
    public function restore(InscriptionFrais $frais) : InscriptionFrais
    {
        try {
            $frais->dehistoriser();
            $this->getEntityManager()->flush($frais);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $frais;
    }

    /**
     * @param InscriptionFrais $frais
     * @return InscriptionFrais
     */
    public function delete(InscriptionFrais $frais) : InscriptionFrais
    {
        try {
            $this->getEntityManager()->remove($frais);
            $this->getEntityManager()->flush($frais);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $frais;
    }
}