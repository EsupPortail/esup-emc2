<?php

namespace Application\Service\FicheProfil;

use Application\Entity\Db\FicheProfil;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use Structure\Entity\Db\Structure;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class FicheProfilService {
    use EntityManagerAwareTrait;
    use StructureServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheProfil $ficheprofil
     * @return FicheProfil
     */
    public function create(FicheProfil $ficheprofil) : FicheProfil
    {
        try {
            $this->getEntityManager()->persist($ficheprofil);
            $this->getEntityManager()->flush($ficheprofil);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $ficheprofil;
    }

    /**
     * @param FicheProfil $ficheprofil
     * @return FicheProfil
     */
    public function update(FicheProfil $ficheprofil) : FicheProfil
    {
        try {
            $this->getEntityManager()->flush($ficheprofil);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $ficheprofil;
    }

    /**
     * @param FicheProfil $ficheprofil
     * @return FicheProfil
     */
    public function historise(FicheProfil $ficheprofil) : FicheProfil
    {
        try {
            $ficheprofil->historiser();
            $this->getEntityManager()->flush($ficheprofil);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $ficheprofil;
    }

    /**
     * @param FicheProfil $ficheprofil
     * @return FicheProfil
     */
    public function restore(FicheProfil $ficheprofil) : FicheProfil
    {
        try {
            $ficheprofil->dehistoriser();
            $this->getEntityManager()->flush($ficheprofil);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $ficheprofil;
    }

    /**
     * @param FicheProfil $ficheprofil
     * @return FicheProfil
     */
    public function delete(FicheProfil $ficheprofil) : FicheProfil
    {
        try {
            $this->getEntityManager()->remove($ficheprofil);
            $this->getEntityManager()->flush($ficheprofil);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $ficheprofil;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(FicheProfil::class)->createQueryBuilder('profil')
            ->addSelect('structure')->join('profil.structure', 'structure')
            ->addSelect('ficheposte')->join('profil.ficheposte', 'ficheposte')
            ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return FicheProfil[]
     */
    public function getFichesProfils(string $champ = 'id', string $ordre='ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('profil.' . $champ, $ordre)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $id
     * @return FicheProfil|null
     */
    public function getFicheProfil(int $id) : ?FicheProfil
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('profil.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FicheProfil partagent le même id [".$id."].");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return FicheProfil|null
     */
    public function getRequestedFicheProfil(AbstractActionController $controller, string $param = 'fiche-profil') : ?FicheProfil
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getFicheProfil($id);
        return $result;
    }

    /**
     * @param Structure $structure
     * @param bool $avecSousStructure
     * @return FicheProfil[]
     */
    public function getFichesPostesByStructure(Structure $structure, bool $avecSousStructure = true) : array
    {
        $structures = [];
        $structures[] = $structure;

        if ($avecSousStructure === true) {
            $structures = $this->getStructureService()->getStructuresFilles($structure);
            $structures[] = $structure;
        }

        $qb = $this->createQueryBuilder()
            ->andWhere('profil.structure in (:structures)')
            ->setParameter('structures', $structures);

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}