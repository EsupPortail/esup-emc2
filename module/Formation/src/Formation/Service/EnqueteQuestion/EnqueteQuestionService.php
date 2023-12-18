<?php

namespace Formation\Service\EnqueteQuestion;

use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\EnqueteQuestion;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class EnqueteQuestionService {
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param EnqueteQuestion $question
     * @return EnqueteQuestion
     */
    public function create(EnqueteQuestion $question) : EnqueteQuestion
    {
        $this->getObjectManager()->persist($question);
        $this->getObjectManager()->flush($question);
        return $question;
    }

    /**
     * @param EnqueteQuestion $question
     * @return EnqueteQuestion
     */
    public function update(EnqueteQuestion $question) : EnqueteQuestion
    {
        $this->getObjectManager()->flush($question);
        return $question;
    }

    /**
     * @param EnqueteQuestion $question
     * @return EnqueteQuestion
     */
    public function historise(EnqueteQuestion $question) : EnqueteQuestion
    {
        $question->historiser();
        $this->getObjectManager()->flush($question);
        return $question;
    }

    /**
     * @param EnqueteQuestion $question
     * @return EnqueteQuestion
     */
    public function restore(EnqueteQuestion $question) : EnqueteQuestion
    {
        $question->dehistoriser();
        $this->getObjectManager()->flush($question);
        return $question;
    }

    /**
     * @param EnqueteQuestion $question
     * @return EnqueteQuestion
     */
    public function delete(EnqueteQuestion $question) : EnqueteQuestion
    {
        $this->getObjectManager()->remove($question);
        $this->getObjectManager()->flush($question);
        return $question;
    }

    /** REQUETAGE  ****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(EnqueteQuestion::class)->createQueryBuilder('question');
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

    public function getEnqueteQuestions()
    {
        $qb = $this->createQueryBuilder()
        ->andWhere('question.histoDestruction IS NULL')
    ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/

}