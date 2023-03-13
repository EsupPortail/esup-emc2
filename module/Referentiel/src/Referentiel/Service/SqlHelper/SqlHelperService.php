<?php

namespace Referentiel\Service\SqlHelper;

use Doctrine\DBAL\Driver\Exception as DRV_Exception;
use Doctrine\DBAL\Exception as DBA_Exception;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Persistence\ProvidesObjectManager;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Entity\Db\UserInterface;

class SqlHelperService {
    use ProvidesObjectManager;

    public function executeRequeteRef(EntityManager $entityManager, string $sql, array $params) : array
    {
        try {
            $res = $entityManager->getConnection()->executeQuery($sql, $params);
            try {
                $tmp = $res->fetchAllAssociative();
            } catch (DRV_Exception $e) {
                throw new RuntimeException("Un problème est survenu [DRV_Exception]", 0, $e);
            }
        } catch (DBA_Exception $e) {
            throw new RuntimeException("Un problème est survenu [DBA_Exception]", 0, $e);
        }
        return $tmp;
    }

    /**
     * @param array $item
     * @return UserInterface|null
     */
    public function createUserFromReferentiel(array $item) : ?UserInterface
    {
        $user = new User();
        $user->setUsername($item['login']);
        $user->setDisplayName($item['prenom'].' '.$item['nom_usage']);
        $user->setEmail($item['email']);
        $user->setPassword('ldap');
        $user->setState(1);

        return $user;
    }

    public function fetch(EntityManager $entityManager, string $table, array $correspondance, string $type, string $id) : array
    {
        $columns = [];
        foreach ($correspondance as $s=>$d) {
            if ($type === 'source') $columns[] = $s;
            if ($type === 'destination') $columns[] = $d;
        }
        if ($type === 'destination') $columns[] = 'deleted_on';
        if ($type === 'destination') $columns[] = 'source_id';

        $sql = "select ".implode(" , ", $columns)." from ".$table;
        $data = $this->executeRequeteRef($entityManager, $sql, []);
        $values = [];

        foreach ($data as $item) {
            $values[$item[$id]] = $item;
        }
        return $values;
    }

    //todo que ce passe t'il pour les booleans
    public function echapValue(?string $value) : string
    {
        if ($value === null or $value === '') return "null";
        return "'". str_replace("'","''",$value) . "'";
    }
    public function insert(EntityManager $entityManager, string $table, array $item, array $correspondance, ?string $source = null) : void
    {
        $columns = []; foreach ($correspondance as $d) $columns[] = $d; $columns[] = "created_on";
        if ($source !== null) $columns[] = 'source_id';
        $values = []; foreach ($correspondance as $s => $d) $values[] = $this->echapValue($item[$s]); $values[] = "now()";
        if ($source !== null) $values[] = "'".$source."'";
            $sql  = "insert into ".$table." (".implode(',',$columns).") values (".implode(',',$values).")";
        $this->executeRequeteRef($entityManager, $sql, []);
    }

    public function update(EntityManager $entityManager, string $table, array $item, array $correspondance, string $id, ?string $source = null) : void
    {
        $values = []; foreach ($correspondance as $s => $d) $values[] = $d."=".$this->echapValue($item[$s]);
        if ($source !== null) $values[] = "source_id='".$source."'";
        $values[] = "updated_on=now()";
        $sql  = "update ".$table. " set " . implode(" , ", $values). " where id=:id";
        $this->executeRequeteRef($entityManager, $sql, ["id" => $id]);
    }


    public function restore(EntityManager $entityManager, string $table, string $id) : void
    {
        $sql = "update ".$table." set deleted_on=NULL where id=:id";
        $this->executeRequeteRef($entityManager, $sql, ["id" => $id]);
    }

    public function delete(EntityManager $entityManager, string $table, string $id) : void
    {
        $sql = "update ".$table." set deleted_on=now() where id=:id";
        $this->executeRequeteRef($entityManager, $sql, ["id" => $id]);
    }
}