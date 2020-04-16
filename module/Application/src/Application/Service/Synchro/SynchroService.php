<?php

namespace Application\Service\Synchro;

use Application\Entity\Db\StructureType;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;

class SynchroService {
    use EntityManagerAwareTrait;
    use DateTimeAwareTrait;

    function getResponde($url){
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

        $_debut = microtime(true);
        try {
            $connection->commit();
        } catch (\Exception $e) {
            try {
                $connection->rollBack();
            } catch (ConnectionException $e) {
                throw new RuntimeException("Le rollback a échoué!", null, $e);
            }
            throw new RuntimeException("Le commit a échoué, un rollback a été effectué.", null, $e);
        }
    }

    public function synchrStructureType()
    {
        $url = 'https://octopus.unicaen.fr/api/structure-type';
        $entityClass= 'Application\Entity\Db\StructureType';
        $key = 'structure-type';

        $json = json_decode($this->getResponde($url));
        $entities = $this->getEntityManager()->getRepository($entityClass)->findAll();
        $date = $this->getDateTime();

        $array = [];
        foreach ($entities as $entity) {
            //TODO source id dnas le trait interface
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
                $item->setHisto($date);
                $historized[] = $item;
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

        $a=1;

    }
}