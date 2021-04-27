<?php

namespace Indicateur\Service\Indicateur;

use DateTime;
use Doctrine\DBAL\Exception as DBA_Exception;
use Doctrine\DBAL\Driver\Exception as DBA_Driver_Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Indicateur\Entity\Db\Indicateur;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class IndicateurService {
    use EntityManagerAwareTrait;

/** GESTION DES ENTITES ***********************************************************************************************/

    /**
     * @param Indicateur $indicateur
     * @return Indicateur
     */
    public function create(Indicateur $indicateur) : Indicateur
    {
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
    public function update(Indicateur $indicateur) : Indicateur
    {
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
    public function delete(Indicateur $indicateur) : Indicateur
    {
        try {
            $this->getEntityManager()->remove($indicateur);
            $this->getEntityManager()->flush($indicateur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base.", $e);
        }
        return $indicateur;
    }

    /** GESTION DES VUES **********************************************************************************************/

    /**
     * @param Indicateur $indicateur
     * @return array
     */
    public function fetch(Indicateur $indicateur) : array {
        $sql = "SELECT * FROM " . $indicateur->getViewId();
        try {
            $query = $this->getEntityManager()->getConnection()->prepare($sql);
        } catch (DBA_Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la session.", 0, $e);
        }
        try {
            $query->execute();
            $array = $query->fetchAll();
        } catch (DBA_Driver_Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de des données de l'indicateur.", 0, $e);
        }
        return $array;
    }

    /**
     * @param Indicateur $indicateur
     */
    public function refresh(Indicateur $indicateur)
    {
        $sql = "REFRESH MATERIALIZED VIEW " . $indicateur->getViewId();
        try {
            $query = $this->getEntityManager()->getConnection()->prepare($sql);
        } catch (DBA_Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la session.", 0, $e);
        }
        try {
            $query->execute();
        } catch (DBA_Driver_Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de des données de l'indicateur.", 0, $e);
        }
        $indicateur->setDernierRafraichissement(new DateTime());
        $this->update($indicateur);
    }

    /**
     * @param Indicateur $indicateur
     */
    public function dropView(Indicateur $indicateur)
    {
        $sql = "DROP MATERIALIZED VIEW " . $indicateur->getViewId();
        try {
            $query = $this->getEntityManager()->getConnection()->prepare($sql);
        } catch (DBA_Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la session.", 0, $e);
        }
        try {
            $query->execute();
        } catch (DBA_Driver_Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de des données de l'indicateur.", 0, $e);
        }
    }

    /**
     * @param Indicateur $indicateur
     */
    public function createView(Indicateur $indicateur)
    {
        $sql = "CREATE MATERIALIZED VIEW ".$indicateur->getViewId(). " AS ".$indicateur->getRequete();
        try {
            $query = $this->getEntityManager()->getConnection()->prepare($sql);
        } catch (DBA_Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la session.", 0, $e);
        }
        try {
            $query->execute();
        } catch (DBA_Driver_Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de des données de l'indicateur.", 0, $e);
        }
        $indicateur->setDernierRafraichissement(new DateTime());
        $this->update($indicateur);
    }

    /**
     * @param Indicateur $indicateur
     */
    public function updateView(Indicateur $indicateur)
    {
        $this->dropView($indicateur);
        $this->createView($indicateur);
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @param string $attribut
     * @param string $ordre
     * @return Indicateur[]
     */
    public function getIndicateurs(string $attribut = 'id', string $ordre = 'ASC') : array
    {
        $qb = $this->getEntityManager()->getRepository(Indicateur::class)->createQueryBuilder('indicateur')
            ->orderBy('indicateur.' . $attribut, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int|null $id
     * @return Indicateur|null
     */
    public function getIndicateur(?int $id) : ?Indicateur
    {
        if ($id === null) return null;

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
     * @return Indicateur|null
     */
    public function getRequestedIndicateur(AbstractActionController $controller, string $paramName = "indicateur") : ?Indicateur
    {
        $id = $controller->params()->fromRoute($paramName);
        return $this->getIndicateur($id);
    }

    /** RECUPERATION DONNEES *****************************************************************************************
     * @param Indicateur $indicateur
     * @return array
     */

    public function getIndicateurData(Indicateur $indicateur)
    {
        $rawdata = $this->fetch($indicateur);
        $rubriques = [];

        if ($indicateur->getEntity() === Indicateur::ENTITY_LIBRE) {
            if (!empty($rawdata)) {
                foreach ($rawdata[0] as $key => $value) $rubriques[] = $key;
            }
        }
        if ($indicateur->getEntity() === Indicateur::ENTITY_ADAPTATIF) {
            if (!empty($rawdata)) {
                foreach ($rawdata[0] as $key => $value) $rubriques[] = $key;
            }
        }
        if ($indicateur->getEntity() === Indicateur::ENTITY_STRUCTURE) {
            $rubriques = [
                'Code'                  => 'code',
                'Libelle'               => 'libelle_court',
                'Libelle long'          => 'libelle_long',
                'Type'                  => 'type',

            ];
        }
        if ($indicateur->getEntity() === Indicateur::ENTITY_AGENT) {
            $rubriques = [
                'ID'                    => 'c_src_individu',
                'SOURCE'                => 'c_source',
                'Prenom'                => 'prenom',
                'Nom'                   => 'nom_usage',
            ];
        }

        $data = [];
        foreach ($rawdata as $rawitem) {
            $item = [];
            foreach ($rubriques as $libelle => $code) {
                $item[] = $rawitem[$code];
            }
            $data[] = $item;
        }

        return [$rubriques, $data];
    }


}