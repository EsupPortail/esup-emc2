<?php

namespace Formation\Service\EnqueteQuestion;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\EnqueteQuestion;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class EnqueteQuestionService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param EnqueteQuestion $question
     * @return EnqueteQuestion
     */
    public function create(EnqueteQuestion $question) : EnqueteQuestion
    {
        try {
            $this->getEntityManager()->persist($question);
            $this->getEntityManager()->flush($question);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survnue en base pour une entité [EnqueteQuestion]",0, $e);
        }
        return $question;
    }

    /**
     * @param EnqueteQuestion $question
     * @return EnqueteQuestion
     */
    public function update(EnqueteQuestion $question) : EnqueteQuestion
    {
        try {
            $this->getEntityManager()->flush($question);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survnue en base pour une entité [EnqueteQuestion]",0, $e);
        }
        return $question;
    }

    /**
     * @param EnqueteQuestion $question
     * @return EnqueteQuestion
     */
    public function historise(EnqueteQuestion $question) : EnqueteQuestion
    {
        try {
            $question->historiser();
            $this->getEntityManager()->flush($question);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survnue en base pour une entité [EnqueteQuestion]",0, $e);
        }
        return $question;
    }

    /**
     * @param EnqueteQuestion $question
     * @return EnqueteQuestion
     */
    public function restore(EnqueteQuestion $question) : EnqueteQuestion
    {
        try {
            $question->dehistoriser();
            $this->getEntityManager()->flush($question);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survnue en base pour une entité [EnqueteQuestion]",0, $e);
        }
        return $question;
    }

    /**
     * @param EnqueteQuestion $question
     * @return EnqueteQuestion
     */
    public function delete(EnqueteQuestion $question) : EnqueteQuestion
    {
        try {
            $this->getEntityManager()->remove($question);
            $this->getEntityManager()->flush($question);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survnue en base pour une entité [EnqueteQuestion]",0, $e);
        }
        return $question;
    }

    /** REQUETAGE  ****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(EnqueteQuestion::class)->createQueryBuilder('question');
        return $qb;
    }

    /**
     * @param int|null $id
     * @return EnqueteQuestion|null
     */
    public function getEnqueteQuestion(?int $id) : ?EnqueteQuestion
    {
        $qb = $this->createQueryBuilder()->andWhere('question.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EnqueteQuestion partagent le même id [".$id."]", 0 , $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return EnqueteQuestion|null
     */
    public function getRequestedEnqueteQuestion(AbstractActionController $controller, string $param = 'question') : ?EnqueteQuestion
    {
        $id = $controller->params()->fromRoute($param);
        $categorie = $this->getEnqueteQuestion($id);
        return $categorie;
    }

    /** FACADE ********************************************************************************************************/

}