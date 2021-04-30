<?php

namespace Application\Service\FicheProfil;

use Application\Entity\Db\FicheProfil;
use Application\Entity\Db\Structure;
use Application\Service\GestionEntiteHistorisationTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class FicheProfilService {
    use GestionEntiteHistorisationTrait;
    use StructureServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheProfil $ficheprofil
     * @return FicheProfil
     */
    public function create(FicheProfil $ficheprofil)
    {
        $this->createFromTrait($ficheprofil);
        return $ficheprofil;
    }

    /**
     * @param FicheProfil $ficheprofil
     * @return FicheProfil
     */
    public function update(FicheProfil $ficheprofil)
    {
        $this->updateFromTrait($ficheprofil);
        return $ficheprofil;
    }

    /**
     * @param FicheProfil $ficheprofil
     * @return FicheProfil
     */
    public function historise(FicheProfil $ficheprofil)
    {
        $this->historiserFromTrait($ficheprofil);
        return $ficheprofil;
    }

    /**
     * @param FicheProfil $ficheprofil
     * @return FicheProfil
     */
    public function restore(FicheProfil $ficheprofil)
    {
        $this->restoreFromTrait($ficheprofil);
        return $ficheprofil;
    }

    /**
     * @param FicheProfil $ficheprofil
     * @return FicheProfil
     */
    public function delete(FicheProfil $ficheprofil)
    {
        $this->deleteFromTrait($ficheprofil);
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
    public function getFichesProfils($champ = 'id', $ordre='ASC') : array
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
    public function getRequestedFicheProfil(AbstractActionController $controller, $param = 'fiche-profil') : ?FicheProfil
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
        }

        $qb = $this->createQueryBuilder()
            ->andWhere('profil.structure in (:structures)')
            ->setParameter('structures', $structures);

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}