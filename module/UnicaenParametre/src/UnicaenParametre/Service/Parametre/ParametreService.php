<?php

namespace UnicaenParametre\Service\Parametre;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenParametre\Entity\Db\Categorie;
use UnicaenParametre\Entity\Db\Parametre;
use Zend\Mvc\Controller\AbstractActionController;

class ParametreService
{
    use EntityManagerAwareTrait;

    /** GESTION ENTITY ************************************************************************************************/

    /**
     * @param Parametre $parametre
     * @return Parametre
     */
    public function create(Parametre $parametre) : Parametre
    {
        try {
            $this->getEntityManager()->persist($parametre);
            $this->getEntityManager()->flush($parametre);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de l'enregistrement en base.",0,$e);
        }
        return $parametre;
    }

    /**
     * @param Parametre $parametre
     * @return Parametre
     */
    public function update(Parametre $parametre) : Parametre
    {
        try {
            $this->getEntityManager()->flush($parametre);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la mise à jour en base.",0,$e);
        }
        return $parametre;
    }

    /**
     * @param Parametre $parametre
     * @return Parametre
     */
    public function delete(Parametre $parametre) : Parametre
    {
        try {
            $this->getEntityManager()->remove($parametre);
            $this->getEntityManager()->flush($parametre);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la suppression en base.",0,$e);
        }
        return $parametre;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Parametre::class)->createQueryBuilder('parametre')
            ->addSelect('categorie')->join('parametre.categorie', 'categorie');
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Parametre[]
     */
    public function getParametres(string $champ = 'ordre', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('parametre.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Categorie $categorie
     * @param string $champ
     * @param string $ordre
     * @return Parametre[]
     */
    public function getParametresByCategorie(Categorie $categorie, string $champ = 'ordre', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('parametre.categorie = :categorie')
            ->setParameter('categorie', $categorie)
            ->orderBy('parametre.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $id
     * @return Parametre|null
     */
    public function getParametre(int $id) : ?Parametre
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('parametre.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Parametre partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    /**
     * @param string $categorieCode
     * @param string $parametreCode
     * @return Parametre|null
     */
    public function getParametreByCode(string $categorieCode, string $parametreCode ) : ?Parametre
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('categorie.code = :categorieCode')
            ->andWhere('parametre.code = :parametreCode')
            ->setParameter('categorieCode', $categorieCode)
            ->setParameter('parametreCode', $parametreCode)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Parametre partagent le même code [".$categorieCode ." - ".$parametreCode."]",0,$e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Parametre
     */
    public function getRequestedParametre(AbstractActionController $controller, string $param = 'parametre')
    {
        $id = $controller->params()->fromRoute($param);
        /** @var Parametre $parametre */
        $parametre =  $this->getParametre($id);
        return $parametre;
    }

}