<?php

namespace Application\Service\Expertise;

use Application\Entity\Db\Expertise;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class ExpertiseService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function create(Expertise $expertise) : Expertise
    {
        try {
            $this->getEntityManager()->persist($expertise);
            $this->getEntityManager()->flush($expertise);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $expertise;
    }

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function update(Expertise $expertise) : Expertise
    {
        try {
            $this->getEntityManager()->flush($expertise);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $expertise;
    }

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function historise(Expertise $expertise) : Expertise
    {
        try {
            $expertise->historiser();
            $this->getEntityManager()->flush($expertise);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $expertise;
    }

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function restore(Expertise $expertise) : Expertise
    {
        try {
            $expertise->dehistoriser();
            $this->getEntityManager()->flush($expertise);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $expertise;
    }

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function delete(Expertise $expertise) : Expertise
    {
        try {
            $this->getEntityManager()->remove($expertise);
            $this->getEntityManager()->flush($expertise);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $expertise;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Expertise::class)->createQueryBuilder('expertise')
            ->addSelect('createur')->join('expertise.histoCreateur', 'createur')
            ->addSelect('modificateur')->join('expertise.histoModificateur', 'modificateur')
            ->addSelect('destructeur')->leftJoin('expertise.histoDestructeur', 'destructeur')
            ->addSelect('ficheposte')->leftJoin('expertise.ficheposte', 'ficheposte')
        ;
        return $qb;
    }

    /**
     * @param $id
     * @return Expertise
     */
    public function getExpertise($id) : ?Expertise
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('expertise.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Expertise partagent le même id [".$id."]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Expertise
     */
    public function getRequestedExpertise($controller, $param = 'expertise') : ?Expertise
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getExpertise($id);
        return $result;
    }
}
