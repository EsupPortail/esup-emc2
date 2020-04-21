<?php

namespace Application\Service\Synchro;

use Application\Entity\Db\Structure;
use Application\Entity\Db\SynchroJob;
use Application\Entity\Db\SynchroLog;
use Application\Entity\SynchroAwareInterface;
use DateTime;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\DBALException;
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
        $table          = $job->getTable();

        $json = json_decode($this->getResponse($url));
        $entities = $this->getEntityManager()->getRepository($entityClass)->findAll();
        $date = $this->getDateTime();
        $wsAttributes = explode(",",$job->getWsAttributes());
        $dbAttributes = explode(",",$job->getDbAttributes());
        $enAttributes = explode(",",$job->getEnAttributes());
        $nbElement = count($wsAttributes);

        /** @var SynchroAwareInterface $entityDb */
        $array = [];
        foreach ($entities as $entityDb) {
            $array[$entityDb->get($enAttributes[0])] = $entityDb;
        }

        $trouver = [];
        $created = [];
        $updated = [];
        $historized = [];

        $str_date = $date->format('Y-m-d H:i:s') . ".000000";


        foreach ($json->{'_embedded'}->{$key} as $entityWs) {
            $reference = $entityWs->{$wsAttributes[0]};
            $trouver[] = $reference;

            //Recuperation de l'entite et de donnée
            $entityDb = new $entityClass;
            $wsValues = [];
            for($position = 0 ; $position < $nbElement ; $position++) {
                $valeurDepuisWS = $entityWs->{$wsAttributes[$position]};
                if (is_array($valeurDepuisWS)) {
                    $valeurDepuisWS = $valeurDepuisWS[0];
                }
                $wsValues[$dbAttributes[$position]] = $valeurDepuisWS;
            }
            $wsValues['synchro'] = $str_date;

            if ($array[$reference]) {
                // update
                $change = false;
                for($position = 0 ; $position < $nbElement ; $position++) {
                    $valeurDepuisWS = $entityWs->{$wsAttributes[$position]};
                    if (is_array($valeurDepuisWS)) {
                        $valeurDepuisWS = $valeurDepuisWS[0];
                    }
                    $wsValues[$dbAttributes[$position]] = $valeurDepuisWS;
                    $valeurDepuisEN = $array[$reference]->get($enAttributes[$position]);
                    if ($valeurDepuisEN instanceof DateTime) {
                        $valeurDepuisEN = $this->protect($valeurDepuisEN);
                        $valeurDepuisWS = $valeurDepuisWS->date;
                    }
                    if ($valeurDepuisEN !== $valeurDepuisWS) {
                        $change = true;
                        break;
                    }
                }
                if ($change) $updated[] = $wsValues;
            } else {
                // create
                $created[] = $wsValues;
            }
        }

        foreach ($array as $item) {
            if ($item->get($enAttributes[0]) AND array_search($item->get($enAttributes[0]), $trouver) === false) {
                if ($item->getHisto() === null) {
                    //histo
                    $item->setHisto($date);
                    $value = $item->get($enAttributes[0]);
                    $data  = [
                        $dbAttributes[0] => $value,
                    ];
                    $historized[] =  $data;
                }
            }
        }

        $fullSQL = "";
        $connection = $this->entityManager->getConnection();
        $connection->beginTransaction();
        try {
            foreach ($created as $values) {
                $attributes = array_keys($values);
                $sql = $this->generateCreateSQL($table, $attributes, $values);
                $fullSQL .= $sql . "<br/>";
                $connection->executeQuery($sql);
            }
            foreach ($updated as $values) {
                $attributes = array_keys($values);
                $sql = $this->generateUpdateSQL($table, $attributes, $values);
                $fullSQL .= $sql . "<br/>";
                $connection->executeQuery($sql);
            }
            foreach ($historized as $values) {
                $attributes = array_keys($values);
                $sql = $this->generateHistoriseSQL($table, $attributes, $values, $str_date);
                $fullSQL .= $sql . "<br/>";
                $connection->executeQuery($sql);
            }
            $connection->commit();
        } catch(DBALException $e) {
            try {
                $connection->rollBack();
            } catch (ConnectionException $e) {
                throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD et le rollback a échoué.", 0, $e);
            }
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", 0, $e);
        }


        $log = new SynchroLog();
        $log->setDate($date);
        $log->setJob($job);
        $log->setRapport("Ajout: " . count($created) . " élément(s)<br/>Mise à jour: " . count($updated) . " élément(s)<br/>Historisation: " . count($historized) ." élément(s).");
        $log->setSql($fullSQL);

        try {
            $this->getEntityManager()->persist($log);
            $this->getEntityManager()->flush($log);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", 0, $e);
        }

        return $log;
    }

    public function generateCreateSQL($table, $attributes, $values)
    {
//        var_dump($values);
        $str_attributes = implode(',', $attributes);
        $values         = array_map( function($v) { return $this->protect($v);/*"'".$v."'");*/} , $values);
        $str_values     = implode(',', $values);

        $sql = "INSERT INTO " . $table . "(" . $str_attributes . ") VALUES (" . $str_values . ")";
//        var_dump($sql);
        return $sql;
    }

    public function generateUpdateSQL($table, $attributes, $values)
    {

        $attributes[] = "histo";
        $values[] = null;


        $sql = "UPDATE " . $table . " SET ";
        $first = true;
        for ($position = 1; $position < count($attributes) ; $position++) {
            if (!$first) $sql .= ", ";
            $sql .= $attributes[$position] . "=" . $this->protect($values[$attributes[$position]]) ." ";
            $first = false;
        }
        $sql .= "WHERE ".$attributes[0] . "=" . $this->protect($values[$attributes[0]]);
//        var_dump($sql);
        return $sql;
    }

    public function generateHistoriseSQL($table, $attributes, $values, $date) {
        $sql = "UPDATE " . $table . " SET ";
        $sql .=  "histo='" . $date."' ";
        $sql .= "WHERE ".$attributes[0] . "=" . $this->protect($values[$attributes[0]]);
//        var_dump($sql);
        return $sql;
    }

    public function protect($value) {
        if ($value === null) return "null";
        if (is_string($value)) return "'" . str_replace("'", "''",$value) ."'";
        if ($value instanceof DateTime) return $value->format('Y-m-d H:i:s') . ".000000";
        if ($value instanceof \stdClass AND $value->date) return "'" .$value->date ."'";
        return $value;
    }
}