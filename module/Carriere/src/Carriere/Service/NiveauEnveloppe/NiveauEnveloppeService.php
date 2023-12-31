<?php

namespace Carriere\Service\NiveauEnveloppe;

use Carriere\Entity\Db\NiveauEnveloppe;
use Doctrine\ORM\Exception\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class NiveauEnveloppeService {

    use EntityManagerAwareTrait;

    /** GESTIONS DES ENTITES ******************************************************************************************/

    public function create(NiveauEnveloppe $metierNiveau) : NiveauEnveloppe
    {
        try {
            $this->getEntityManager()->persist($metierNiveau);
            $this->getEntityManager()->flush($metierNiveau);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $metierNiveau;
    }

    public function update(NiveauEnveloppe $metierNiveau) : NiveauEnveloppe
    {
        try {
            $this->getEntityManager()->flush($metierNiveau);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $metierNiveau;
    }

    public function historise(NiveauEnveloppe $metierNiveau) : NiveauEnveloppe
    {
        try {
            $metierNiveau->historiser();
            $this->getEntityManager()->flush($metierNiveau);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $metierNiveau;
    }

    public function restore(NiveauEnveloppe $metierNiveau) : NiveauEnveloppe
    {
        try {
            $metierNiveau->dehistoriser();
            $this->getEntityManager()->flush($metierNiveau);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $metierNiveau;
    }

    public function delete(NiveauEnveloppe $metierNiveau) : NiveauEnveloppe
    {
        try {
            $this->getEntityManager()->remove($metierNiveau);
            $this->getEntityManager()->flush($metierNiveau);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $metierNiveau;
    }
}