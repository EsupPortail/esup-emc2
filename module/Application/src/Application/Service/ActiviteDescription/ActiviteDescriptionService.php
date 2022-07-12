<?php

namespace Application\Service\ActiviteDescription;

use Application\Entity\Db\ActiviteDescription;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class ActiviteDescriptionService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param ActiviteDescription $description
     * @return ActiviteDescription
     */
    public function create(ActiviteDescription $description) : ActiviteDescription
    {
        try {
            $this->getEntityManager()->persist($description);
            $this->getEntityManager()->flush($description);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $description;
    }

    /**
     * @param ActiviteDescription $description
     * @return ActiviteDescription
     */
    public function update(ActiviteDescription $description) : ActiviteDescription
    {
        try {
            $this->getEntityManager()->flush($description);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $description;
    }

    /**
     * @param ActiviteDescription $description
     * @return ActiviteDescription
     */
    public function historise(ActiviteDescription $description) : ActiviteDescription
    {
        try {
            $description->historiser();
            $this->getEntityManager()->flush($description);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $description;
    }

    /**
     * @param ActiviteDescription $description
     * @return ActiviteDescription
     */
    public function restore(ActiviteDescription $description) : ActiviteDescription
    {
        try {
            $description->dehistoriser();
            $this->getEntityManager()->flush($description);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $description;
    }

    /**
     * @param ActiviteDescription $description
     * @return ActiviteDescription
     */
    public function delete(ActiviteDescription $description) : ActiviteDescription
    {
        try {
            $this->getEntityManager()->remove($description);
            $this->getEntityManager()->flush($description);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $description;
    }

    /** ACCESSEUR *****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(ActiviteDescription::class)->createQueryBuilder('description')
            ->addSelect('activite')->join('description.activite', 'activite')
        ;
        return $qb;
    }

    /**
     * @param integer $id
     * @return ActiviteDescription
     */
    public function getActiviteDescription(int $id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('description.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ActiviteDescription partagent le même id [".$id."]", 0 , $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return ActiviteDescription
     */
    public function getRequestedActiviteDescription(AbstractActionController $controller, $param = 'description')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getActiviteDescription($id);
        return $result;
    }
}