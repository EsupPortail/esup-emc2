<?php

namespace Formation\Service\EnqueteCategorie;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\EnqueteCategorie;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;

class EnqueteCategorieService {
    use ProvidesObjectManager;


    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param EnqueteCategorie $question
     * @return EnqueteCategorie
     */
    public function create(EnqueteCategorie $question) : EnqueteCategorie
    {
        $this->getObjectManager()->persist($question);
        $this->getObjectManager()->flush($question);
        return $question;
    }

    /**
     * @param EnqueteCategorie $question
     * @return EnqueteCategorie
     */
    public function update(EnqueteCategorie $question) : EnqueteCategorie
    {
        $this->getObjectManager()->flush($question);
        return $question;
    }

    /**
     * @param EnqueteCategorie $question
     * @return EnqueteCategorie
     */
    public function historise(EnqueteCategorie $question) : EnqueteCategorie
    {
        $question->historiser();
        $this->getObjectManager()->flush($question);
        return $question;
    }

    /**
     * @param EnqueteCategorie $question
     * @return EnqueteCategorie
     */
    public function restore(EnqueteCategorie $question) : EnqueteCategorie
    {
        $question->dehistoriser();
        $this->getObjectManager()->flush($question);
        return $question;
    }

    /**
     * @param EnqueteCategorie $question
     * @return EnqueteCategorie
     */
    public function delete(EnqueteCategorie $question) : EnqueteCategorie
    {
        $this->getObjectManager()->remove($question);
        $this->getObjectManager()->flush($question);
        return $question;
    }

    /** REQUETAGE  ****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(EnqueteCategorie::class)->createQueryBuilder('categorie');
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

    public function getCategoriesAsOptions() : array
    {
        /** @var EnqueteCategorie $categories */
        $categories = $this->getObjectManager()->getRepository(EnqueteCategorie::class)->findAll();
        $options = [];
        foreach ($categories as $categorie) {
            $options[$categorie->getId()] = $categorie->getLibelle();
        }
        return $options;
    }

    /** FACADE ********************************************************************************************************/

}