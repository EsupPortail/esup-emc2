<?php

namespace Application\Service\Expertise;

use Application\Entity\Db\Expertise;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ExpertiseService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;
    use DateTimeAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function create(Expertise $expertise)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $expertise->setHistoCreateur($user);
        $expertise->setHistoCreation($date);
        $expertise->setHistoModificateur($user);
        $expertise->setHistoModification($date);

        try {
            $this->getEntityManager()->persist($expertise);
            $this->getEntityManager()->flush($expertise);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0, $e);
        }

        return $expertise;
    }

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function update(Expertise $expertise)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $expertise->setHistoModificateur($user);
        $expertise->setHistoModification($date);

        try {
            $this->getEntityManager()->flush($expertise);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0, $e);
        }

        return $expertise;
    }

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function historise(Expertise $expertise)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $expertise->setHistoDestructeur($user);
        $expertise->setHistoDestruction($date);

        try {
            $this->getEntityManager()->flush($expertise);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0, $e);
        }

        return $expertise;
    }

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function restore(Expertise $expertise)
    {
        $expertise->setHistoDestructeur(null);
        $expertise->setHistoDestruction(null);

        try {
            $this->getEntityManager()->flush($expertise);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0, $e);
        }

        return $expertise;
    }

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function delete(Expertise $expertise)
    {
        try {
            $this->getEntityManager()->remove($expertise);
            $this->getEntityManager()->flush($expertise);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0, $e);
        }

        return $expertise;
    }


    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(Expertise::class)->createQueryBuilder('expertise');
        return $qb;
    }

    /**
     * @param $id
     * @return Expertise
     */
    public function getExpertise($id)
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
    public function getRequestedExpertise($controller, $param = 'expertise')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getExpertise($id);
        return $result;
    }
}
