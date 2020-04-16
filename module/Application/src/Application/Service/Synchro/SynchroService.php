<?php

namespace Application\Service\Synchro;

use Application\Entity\Db\SynchroJob;
use Application\Entity\Db\SynchroLog;
use Application\Entity\SynchroAwareInterface;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;

class SynchroService {
    use EntityManagerAwareTrait;
    use DateTimeAwareTrait;

    /** REQUETAGE DES ENTITES *****************************************************************************************/

    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(SynchroJob::class)->createQueryBuilder('synchro')
            ->addSelect('log')->leftJoin('synchro.logs', 'log')
        ;
        return $qb;
    }

    /**
     * @return SynchroJob[]
     */
    public function getSynchroJobs()
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('synchro.key')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $key
     * @return SynchroJob
     */
    public function getSynchroJob(string $key)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('synchro.key = :key')
            ->setParameter('key', $key)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs SynchroJob partagent la même clef [".$key."]", 0, $e);
        }
        return $result;
    }

    /** PARTIE FONCTIONNELLE ******************************************************************************************/

    /**
     * @param string $url
     * @return bool|string
     */
    function getResponse($url){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        $xmlstr = curl_exec($ch);
        curl_close($ch);

        return $xmlstr;
    }

    /** Débute une transaction en bdd. */
    public function beginTransaction()
    {
        $connection = $this->entityManager->getConnection();
        $connection->beginTransaction();
    }

    /** Fait un commit en bdd. */
    public function commit()
    {
        $connection = $this->entityManager->getConnection();

//        $_debut = microtime(true);
        try {
            $connection->commit();
        } catch (Exception $e) {
            try {
                $connection->rollBack();
            } catch (ConnectionException $e) {
                throw new RuntimeException("Le rollback a échoué!", null, $e);
            }
            throw new RuntimeException("Le commit a échoué, un rollback a été effectué.", null, $e);
        }
    }

    /**
     * @param SynchroJob $job
     * @return SynchroLog
     */
    public function synchronize(SynchroJob $job)
    {
        $url            = $job->getUrl();
        $entityClass    = $job->getEntityClass();
        $key            = $job->getKey();

        $json = json_decode($this->getResponse($url));
        $entities = $this->getEntityManager()->getRepository($entityClass)->findAll();
        $date = $this->getDateTime();

        /** @var SynchroAwareInterface $entity */

        $array = [];
        foreach ($entities as $entity) {
            $array[$entity->getSourceId()] = $entity;
        }

        $trouver = [];
        $created = [];
        $updated = [];
        $historized = [];

        foreach ($json->{'_embedded'}->{$key} as $structureType) {
            $source_id = $structureType->{'id'};
            $code = $structureType->{'code'};
            $libelle = $structureType->{'libelle'};
            $trouver[] = $source_id;

            if ($array[$source_id]) {
                // update
                if ($array[$source_id]->getCode() !== $code OR $array[$source_id]->getLibelle() !== $libelle) {
                    $array[$source_id]->setCode($code);
                    $array[$source_id]->setLibelle($libelle);
                    $array[$source_id]->setSynchro($date);
                    $updated[] = $array[$source_id];
                }
                if ($array[$source_id]->getHisto()) {
                    $array[$source_id]->setSynchro($date);
                    $array[$source_id]->setHisto(null);
                    $updated[] = $array[$source_id];
                }
            } else {
                // create
                $entity = new $entityClass;
                $entity->setSourceId($source_id);
                $entity->setCode($code);
                $entity->setLibelle($libelle);
                $entity->setSynchro($date);
                $created[] = $entity;
            }
        }

        foreach ($array as $item) {
            if ($item->getSourceId() AND array_search($item->getSourceId(), $trouver) === false) {
                if ($item->getHisto() === null) {
                    $item->setHisto($date);
                    $historized[] = $item;
                }
            }
        }

        //todo transaction
        try {
            foreach ($created as $item) {
                $this->getEntityManager()->persist($item);
                $this->getEntityManager()->flush($item);
            }
            foreach ($updated as $item) {
                $this->getEntityManager()->flush($item);
            }
            foreach ($historized as $item) {
                $this->getEntityManager()->flush($item);
            }
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", 0, $e);
        }

        $log = new SynchroLog();
        $log->setDate($date);
        $log->setJob($job);
        $log->setRapport("Ajout: " . count($created) . " élément(s)<br/>Mise à jour: " . count($updated) . " élément(s)<br/>Historisation: " . count($historized) ." élément(s)");

        try {
            $this->getEntityManager()->persist($log);
            $this->getEntityManager()->flush($log);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", 0, $e);
        }

        return $log;

    }
}