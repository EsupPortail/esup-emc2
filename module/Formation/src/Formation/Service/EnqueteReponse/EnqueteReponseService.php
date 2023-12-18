<?php

namespace Formation\Service\EnqueteReponse;

use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\EnqueteReponse;
use Formation\Entity\Db\Inscription;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class EnqueteReponseService {
    use EntityManagerAwareTrait;


    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param EnqueteReponse $reponse
     * @return EnqueteReponse
     */
    public function create(EnqueteReponse $reponse) : EnqueteReponse
    {
        try {
            $this->getEntityManager()->persist($reponse);
            $this->getEntityManager()->flush($reponse);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survnue en base pour une entité [EnqueteReponse]",0, $e);
        }
        return $reponse;
    }

    /**
     * @param EnqueteReponse $reponse
     * @return EnqueteReponse
     */
    public function update(EnqueteReponse $reponse) : EnqueteReponse
    {
        try {
            $this->getEntityManager()->flush($reponse);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survnue en base pour une entité [EnqueteReponse]",0, $e);
        }
        return $reponse;
    }

    /**
     * @param EnqueteReponse $reponse
     * @return EnqueteReponse
     */
    public function historise(EnqueteReponse $reponse) : EnqueteReponse
    {
        try {
            $reponse->historiser();
            $this->getEntityManager()->flush($reponse);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survnue en base pour une entité [EnqueteReponse]",0, $e);
        }
        return $reponse;
    }

    /**
     * @param EnqueteReponse $reponse
     * @return EnqueteReponse
     */
    public function restore(EnqueteReponse $reponse) : EnqueteReponse
    {
        try {
            $reponse->dehistoriser();
            $this->getEntityManager()->flush($reponse);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survnue en base pour une entité [EnqueteReponse]",0, $e);
        }
        return $reponse;
    }

    /**
     * @param EnqueteReponse $reponse
     * @return EnqueteReponse
     */
    public function delete(EnqueteReponse $reponse) : EnqueteReponse
    {
        try {
            $this->getEntityManager()->remove($reponse);
            $this->getEntityManager()->flush($reponse);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survnue en base pour une entité [EnqueteReponse]",0, $e);
        }
        return $reponse;
    }

    /** REQUETAGE  ****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(EnqueteReponse::class)->createQueryBuilder('reponse');
        return $qb;
    }

    /**
     * @param int|null $id
     * @return EnqueteReponse|null
     */
    public function getEnqueteReponse(?int $id) : ?EnqueteReponse
    {
        $qb = $this->createQueryBuilder()->andWhere('reponse.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EnqueteReponse partagent le même id [".$id."]", 0 , $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return EnqueteReponse|null
     */
    public function getRequestedEnqueteReponse(AbstractActionController $controller, string $param = 'reponse') : ?EnqueteReponse
    {
        $id = $controller->params()->fromRoute($param);
        $categorie = $this->getEnqueteReponse($id);
        return $categorie;
    }

    /** @return EnqueteReponse[] */
    public function findEnqueteReponseByInscription(Inscription $inscription) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('reponse.inscription = :inscription')->setParameter('inscription', $inscription);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return EnqueteReponse[]
     */
    public function getEnqueteReponses() : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('reponse.histoDestruction IS NULL')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param array $params
     * @return float|int|mixed|string
     */
    public function getEnqueteReponsesWithFiltre(array $params = [])
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('reponse.histoDestruction IS NULL')
            ->join('reponse.inscription', 'inscription')->addSelect('inscription')
            ->join('inscription.instance', 'session')->addSelect('session')
            ->join('session.formateurs', 'formateur')->addSelect('formateur')
            ->join('session.formation', 'formation')->addSelect('formation')
            ->join('session.journees', 'journee')->addSelect('journee')
        ;

        if (isset($params['formation'])) {
            $qb = $qb->andWhere('formation.id = :id')->setParameter('id', $params['formation']->getId());
        }
        if (isset($params['formateur']) AND trim($params['formateur']) !== "") {
            $qb = $qb->andWhere('formateur.email = :email')->setParameter('email', $params['formateur']);
        }
        if (isset($params['annee'])  AND trim($params['annee']) !== "") {
            $debut = DateTime::createFromFormat('d/m/Y', '01/09/'.$params['annee']);
            $fin = DateTime::createFromFormat('d/m/Y', '31/08/'.(((int) $params['annee']) + 1));

            $qb = $qb
                ->andWhere('journee.jour <= :fin')->setParameter('fin', $fin)
                ->andWhere('journee.jour >= :debut')->setParameter('debut', $debut)
            ;
        }

        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/

}