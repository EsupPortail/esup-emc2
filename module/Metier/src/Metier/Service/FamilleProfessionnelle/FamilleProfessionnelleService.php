<?php

namespace Metier\Service\FamilleProfessionnelle;

use Doctrine\ORM\ORMException;
use Metier\Entity\Db\FamilleProfessionnelle;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class FamilleProfessionnelleService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FamilleProfessionnelle $famille
     * @return FamilleProfessionnelle
     */
    public function create(FamilleProfessionnelle $famille) : FamilleProfessionnelle
    {
        try {
            $this->getEntityManager()->persist($famille);
            $this->getEntityManager()->flush($famille);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $famille;
    }

    /**
     * @param FamilleProfessionnelle $famille
     * @return FamilleProfessionnelle
     */
    public function update(FamilleProfessionnelle $famille) : FamilleProfessionnelle
    {
        try {
            $this->getEntityManager()->flush($famille);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $famille;
    }

    /**
     * @param FamilleProfessionnelle $famille
     * @return FamilleProfessionnelle
     */
    public function historise(FamilleProfessionnelle $famille) : FamilleProfessionnelle
    {
        try {
            $famille->historiser();
            $this->getEntityManager()->flush($famille);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $famille;
    }

    /**
     * @param FamilleProfessionnelle $famille
     * @return FamilleProfessionnelle
     */
    public function restore(FamilleProfessionnelle $famille) : FamilleProfessionnelle
    {
        try {
            $famille->dehistoriser();
            $this->getEntityManager()->flush($famille);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $famille;
    }

    /**
     * @param FamilleProfessionnelle $famille
     * @return FamilleProfessionnelle
     */
    public function delete(FamilleProfessionnelle $famille) : FamilleProfessionnelle
    {
        try {
            $this->getEntityManager()->remove($famille);
            $this->getEntityManager()->flush($famille);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $famille;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(FamilleProfessionnelle::class)->createQueryBuilder('famille')
            ->addSelect('domaine')->leftJoin('famille.domaines', 'domaine')
            ->addSelect('metier')->leftJoin('domaine.metiers', 'metier')
        ;
        return $qb;
    }

    /**
     * @return FamilleProfessionnelle[]
     */
    public function getFamillesProfessionnelles() : array
    {
        $qb = $this->createQueryBuilder()
            ->addOrderBy('famille.libelle');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param bool $historiser
     * @return array
     */
    public function getFamillesProfessionnellesAsOptions(bool $historiser = false) : array
    {
        $familles = $this->getFamillesProfessionnelles();
        $options = [];
        foreach ($familles as $famille) {
            if ($historiser OR $famille->estNonHistorise())
                $options[$famille->getId()] = $famille->getLibelle();
        }
        return $options;
    }

    /**
     * @param integer $id
     * @return FamilleProfessionnelle|null
     */
    public function getFamilleProfessionnelle(int $id) : ?FamilleProfessionnelle
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('famille.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FamilleProfessionnelle partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return FamilleProfessionnelle|null
     */
    public function getRequestedFamilleProfessionnelle(AbstractActionController $controller, string $paramName = 'famille-professionnelle') : ?FamilleProfessionnelle
    {
        $id = $controller->params()->fromRoute($paramName);
        $famille = $this->getFamilleProfessionnelle($id);

        return $famille;
    }
}
