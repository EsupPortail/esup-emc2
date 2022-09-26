<?php

namespace Formation\Service\EnqueteCategorie;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\EnqueteCategorie;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class EnqueteCategorieService {
    use EntityManagerAwareTrait;


    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param EnqueteCategorie $question
     * @return EnqueteCategorie
     */
    public function create(EnqueteCategorie $question) : EnqueteCategorie
    {
        try {
            $this->getEntityManager()->persist($question);
            $this->getEntityManager()->flush($question);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survnue en base pour une entité [EnqueteCategorie]",0, $e);
        }
        return $question;
    }

    /**
     * @param EnqueteCategorie $question
     * @return EnqueteCategorie
     */
    public function update(EnqueteCategorie $question) : EnqueteCategorie
    {
        try {
            $this->getEntityManager()->flush($question);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survnue en base pour une entité [EnqueteCategorie]",0, $e);
        }
        return $question;
    }

    /**
     * @param EnqueteCategorie $question
     * @return EnqueteCategorie
     */
    public function historise(EnqueteCategorie $question) : EnqueteCategorie
    {
        try {
            $question->historiser();
            $this->getEntityManager()->flush($question);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survnue en base pour une entité [EnqueteCategorie]",0, $e);
        }
        return $question;
    }

    /**
     * @param EnqueteCategorie $question
     * @return EnqueteCategorie
     */
    public function restore(EnqueteCategorie $question) : EnqueteCategorie
    {
        try {
            $question->dehistoriser();
            $this->getEntityManager()->flush($question);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survnue en base pour une entité [EnqueteCategorie]",0, $e);
        }
        return $question;
    }

    /**
     * @param EnqueteCategorie $question
     * @return EnqueteCategorie
     */
    public function delete(EnqueteCategorie $question) : EnqueteCategorie
    {
        try {
            $this->getEntityManager()->remove($question);
            $this->getEntityManager()->flush($question);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survnue en base pour une entité [EnqueteCategorie]",0, $e);
        }
        return $question;
    }

    /** REQUETAGE  ****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(EnqueteCategorie::class)->createQueryBuilder('categorie');
        return $qb;
    }

    /**
     * @param int|null $id
     * @return EnqueteCategorie|null
     */
    public function getEnqueteCateorie(?int $id) : ?EnqueteCategorie
    {
        $qb = $this->createQueryBuilder()->andWhere('categorie.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EnqueteCategorie partagent le même id [".$id."]", 0 , $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return EnqueteCategorie|null
     */
    public function getRequestedEnqueteCategorie(AbstractActionController $controller, string $param = 'categorie') : ?EnqueteCategorie
    {
        $id = $controller->params()->fromRoute($param);
        $categorie = $this->getEnqueteCateorie($id);
        return $categorie;
    }

    /**
     * @return EnqueteCategorie[]
     */
    public function getEnqueteCateories() : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('categorie.histoDestruction IS NULL')
            ->join('categorie.questions', 'question')->addSelect('question')
            ->andWhere('question.histoDestruction IS NULL')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;

    }

    /** FACADE ********************************************************************************************************/

    public function getCategoriesAsOptions() : array
    {
        /** @var EnqueteCategorie $categories */
        $categories = $this->getEntityManager()->getRepository(EnqueteCategorie::class)->findAll();
        $options = [];
        foreach ($categories as $categorie) {
            $options[$categorie->getId()] = $categorie->getLibelle();
        }
        return $options;
    }


}