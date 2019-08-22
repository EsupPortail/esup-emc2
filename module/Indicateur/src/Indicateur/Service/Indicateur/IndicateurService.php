<?php

namespace Indicateur\Service\Indicateur;

use DateTime;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;
use Indicateur\Entity\Db\Indicateur;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class IndicateurService {
    use EntityManagerAwareTrait;

    /**
     * @param string $attribut
     * @param string $ordre
     * @return Indicateur[]
     */
    public function getIndicateurs($attribut = 'id', $ordre = 'ASC') {
        $qb = $this->getEntityManager()->getRepository(Indicateur::class)->createQueryBuilder('indicateur')
            ->orderBy('indicateur.' . $attribut, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Indicateur
     */
    public function getIndicateur($id) {
        $qb = $this->getEntityManager()->getRepository(Indicateur::class)->createQueryBuilder('indicateur')
            ->andWhere('indicateur.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Indicateur partagent le même id [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Indicateur
     */
    public function getRequestedIndicateur($controller, $paramName = "indicateur") {
        $id = $controller->params()->fromRoute($paramName);
        return $this->getIndicateur($id);
    }

    /**
     * @param Indicateur $indicateur
     * @return Indicateur
     */
    public function create($indicateur) {
        try {
            $this->getEntityManager()->persist($indicateur);
            $this->getEntityManager()->flush($indicateur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base.", $e);
        }
        return $indicateur;
    }

    /**
     * @param Indicateur $indicateur
     * @return Indicateur
     */
    public function update($indicateur) {
        try {
            $this->getEntityManager()->flush($indicateur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base.", $e);
        }
        return $indicateur;
    }

    /**
     * @param Indicateur $indicateur
     * @return Indicateur
     */
    public function delete($indicateur) {
        try {
            $this->getEntityManager()->remove($indicateur);
            $this->getEntityManager()->flush($indicateur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base.", $e);
        }
        return $indicateur;
    }

    /**
     * @param Indicateur $indicateur
     * @return array
     */
    public function fetch($indicateur) {
        try {
            $sql = "SELECT * FROM " . $indicateur->getViewId();
            $query = $this->getEntityManager()->getConnection()->prepare($sql);
            $query->execute();
            $array = $query->fetchAll();
        } catch (DBALException $e) {
            throw new RuntimeException("Un problème est survenue durant la récupération de l'indicateur [".$indicateur->getTitre()."]", $e);
        }
        return $array;
    }

    /**
     * @param Indicateur $indicateur
     */
    public function refresh($indicateur) {
        try {
            $sql = "REFRESH MATERIALIZED VIEW " . $indicateur->getViewId();
            $query = $this->getEntityManager()->getConnection()->prepare($sql);
            $query->execute();
        } catch (DBALException $e) {
            throw new RuntimeException("Un problème est survenue durant le rafraichissement de l'indicateur [".$indicateur->getTitre()."]", $e);
        }

        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenue durant la récupération de la date", $e);
        }
        $indicateur->setDernierRafraichissement($date);
        $this->update($indicateur);
    }

}