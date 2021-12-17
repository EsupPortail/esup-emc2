<?php

namespace Formation\Service\FormationInstanceFrais;

use Doctrine\ORM\ORMException;
use Formation\Entity\Db\FormationInstanceFrais;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class FormationInstanceFraisService
{
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES **********************************************************************************/

    /**
     * @param FormationInstanceFrais $frais
     * @return FormationInstanceFrais
     */
    public function create(FormationInstanceFrais $frais) : FormationInstanceFrais
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
     * @param FormationInstanceFrais $frais
     * @return FormationInstanceFrais
     */
    public function update(FormationInstanceFrais $frais) : FormationInstanceFrais
    {
        try {
            $this->getEntityManager()->flush($frais);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $frais;
    }

    /**
     * @param FormationInstanceFrais $frais
     * @return FormationInstanceFrais
     */
    public function historise(FormationInstanceFrais $frais) : FormationInstanceFrais
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
     * @param FormationInstanceFrais $frais
     * @return FormationInstanceFrais
     */
    public function restore(FormationInstanceFrais $frais) : FormationInstanceFrais
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
     * @param FormationInstanceFrais $frais
     * @return FormationInstanceFrais
     */
    public function delete(FormationInstanceFrais $frais) : FormationInstanceFrais
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